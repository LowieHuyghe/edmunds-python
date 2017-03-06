
from logging import StreamHandler
from logging import WARNING, Formatter
import sys


class Stream(StreamHandler):
    """
    Stream Driver
    """

    def __init__(self, app, stream=sys.stderr, level=WARNING, format=None):
        """
        Initiate the instance
        :param app:         The application
        :type  app:         Edmunds.Application
        :param stream:      The stream
        :type  stream:      stream
        :param level:       The minimum level to log
        :type  level:       int
        :param format:      The format for the formatter
        :type  format:      str
        """

        super(Stream, self).__init__(stream)

        self.setLevel(level)

        if format is None:
            format = '[%(asctime)s] %(levelname)-8s: %(message)s [in %(pathname)s:%(lineno)d]'
        self.setFormatter(Formatter(format))
