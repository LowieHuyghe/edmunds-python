
from edmunds.profiler.drivers.basedriver import BaseDriver
from pstats import Stats
import sys


class Stream(BaseDriver):
    """
    Stream driver
    """

    def __init__(self, app, stream=sys.stdout, sort_by=('time', 'calls'), restrictions=()):
        """
        Initiate the instance
        :param app:             The application
        :type  app:             Application
        :param stream:          The stream
        :type  stream:          stream
        :param sort_by:         Sort by
        :type  sort_by:         tuple
        :param restrictions:    Restrictions
        :type  restrictions:    tuple
        """

        super(Stream, self).__init__(app)

        self._stream = stream
        self._sort_by = sort_by
        self._restrictions = restrictions

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

        stats = Stats(profiler, stream=self._stream)
        stats.sort_stats(*self._sort_by)

        self._stream.write('-' * 80)
        self._stream.write('\nPATH: %s\n' % environment.get('PATH_INFO'))
        stats.print_stats(*self._restrictions)
        self._stream.write('-' * 80 + '\n\n')
