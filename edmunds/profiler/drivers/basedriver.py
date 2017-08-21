
from edmunds.globals import abc, ABC


class BaseDriver(ABC):
    """
    The base driver for profiler-drivers
    """

    def __init__(self, app):
        """
        Initiate the instance
        :param app:                         The application
        :type  app:                         Edmunds.Application
        """

        self._app = app

    @abc.abstractmethod
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
        pass
