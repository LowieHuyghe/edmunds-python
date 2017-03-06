
from edmunds.profiler.drivers.basedriver import BaseDriver
from pyprof2calltree import CalltreeConverter
import os


class CallGraph(BaseDriver):
    """
    CallGraph driver
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

        super(CallGraph, self).__init__(app)

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
            converter.output(f)
        finally:
            f.close()
