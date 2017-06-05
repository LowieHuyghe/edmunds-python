
from logging.handlers import TimedRotatingFileHandler
from logging import WARNING, Formatter
import os


class TimedFile(TimedRotatingFileHandler):
    """
    Timed File Driver
    """

    def __init__(self, app, log_path, filename, prefix='', when='D', interval=1, backup_count=0, level=WARNING, format=None):
        """
        Initiate the instance
        :param app:             The application
        :type  app:             Application
        :param log_path:        The log path
        :type  log_path:        str
        :param filename:        The filename
        :type  filename:        str
        :param prefix:          The prefix for storing
        :type  prefix:          str
        :param when:            Store when?
        :type  when:            str
        :param interval:        The interval for storing
        :type  interval:        int
        :param backup_count:    The max number of files stored
        :type  backup_count:    int
        :param level:           The minimum level to log
        :type  level:           int
        :param format:          The format for the formatter
        :type  format:          str
        """

        self._app = app
        filename = os.path.join(log_path, prefix + filename)

        super(TimedFile, self).__init__(filename, when=when, interval=interval, backupCount=backup_count, utc=True)

        self.setLevel(level)

        if format is None:
            format = '[%(asctime)s] %(levelname)-8s: %(message)s [in %(pathname)s:%(lineno)d]'
        self.setFormatter(Formatter(format))

    def _open(self):
        """
        Open the current base file with the (original) mode and encoding.
        Return the resulting stream.
        """

        # self.encoding
        # self.mode
        return self._app.fs().write_stream(self.baseFilename, append=True)
