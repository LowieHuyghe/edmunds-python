
from edmunds.profiler.drivers.basedriver import BaseDriver
from pyprof2calltree import CalltreeConverter
import os
import cProfile


class BlackfireIo(BaseDriver):
    """
    Blackfire Io driver
    """

    def __init__(self, app, profile_path, prefix='', suffix=''):
        """
        Initiate the instance
        :param app:             The application
        :type  app:             Application
        :param profile_path:    The profile path
        :type  profile_path:    str
        :param prefix:          The prefix for storing
        :type  prefix:          str
        :param suffix:          The suffix for storing
        :type  suffix:          str
        """

        super(BlackfireIo, self).__init__(app)

        self._profile_path = profile_path
        self._prefix = prefix
        self._suffix = suffix

    def process(self, profiler, start, end, environment, suggestive_file_name):
        """
        Process the results
        :param profiler:                The profiler
        :type  profiler:                cProfile.Profile
        :param start:                   Start of profiling
        :type start:                    int
        :param end:                     End of profiling
        :type end:                      int
        :param environment:             The environment
        :type  environment:             Environment
        :param suggestive_file_name:    A suggestive file name
        :type  suggestive_file_name:    str
        """

        filename = self._prefix + suggestive_file_name + self._suffix
        filepath = os.path.join(self._profile_path, filename)

        converter = CalltreeConverter(profiler.getstats())

        f = self._app.fs().write_stream(filepath)

        try:
            f.write('file-format: BlackfireProbe\n')
            f.write('cost-dimensions: wt\n')
            f.write('request-start: %d\n' % start)
            f.write('profile-title: %s\n' % filename)
            f.write('\n')

            def unique_name(code):
                co_filename, co_firstlineno, co_name = cProfile.label(code)
                munged_name = converter.munged_function_name(code)
                return '%s::%s' % (co_filename, munged_name)

            def _entry_sort_key(entry):
                return cProfile.label(entry.code)

            for entry in sorted(converter.entries, key=_entry_sort_key):

                entry_name = unique_name(entry.code)
                if entry_name.startswith('~::'):
                    continue

                if entry.calls:
                    for subentry in sorted(entry.calls, key=_entry_sort_key):
                        subentry_name = unique_name(subentry.code)
                        if subentry_name.startswith('~::'):
                            continue

                        f.write('%s==>%s//%i %i\n' % (entry_name, subentry_name, subentry.callcount, int(subentry.totaltime * 1e9)))
        finally:
            f.close()
