
from logging import StreamHandler, WARNING, Formatter


class GoogleAppEngine(StreamHandler):
    """
    Google App Engine Driver
    There is nothing special about this driver, but the StreamHandler-class
    is special in the Google App Engine runtime.
    """

    def __init__(self, app, level=WARNING, format=None, stream=None):
        """
        Initiate the instance
        :param app:             The application
        :type  app:             Edmunds.Application
        :param level:           The minimum level to log
        :type  level:           int
        :param format:          The format for the formatter
        :type  format:          str
        :param stream:          The stream. This is only meant for testing.
        :type  stream:          stream
        """

        super(GoogleAppEngine, self).__init__(stream)

        self.setLevel(level)

        if format is None:
            format = '%(levelname)-8s %(asctime)s %(filename)s:%(lineno)s] %(message)s'
        self.setFormatter(Formatter(format))
