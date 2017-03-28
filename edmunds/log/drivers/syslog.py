
from logging.handlers import SysLogHandler, SYSLOG_UDP_PORT
from logging import WARNING, Formatter
from socket import SOCK_DGRAM


class SysLog(SysLogHandler):
    """
    Sys Log Driver
    """

    def __init__(self, app, address=('localhost', SYSLOG_UDP_PORT), facility=SysLogHandler.LOG_USER, socktype=SOCK_DGRAM, level=WARNING, format=None):
        """
        Initiate the instance
        :param app:             The application
        :type  app:             Application
        :param address:         The address
        :type  address:         tuple|str
        :param facility:        The facility to log
        :type  facility:        int
        :param socktype:        The socktype to log
        :type  socktype:        int
        :param level:           The minimum level to log
        :type  level:           int
        :param format:          The format for the formatter
        :type  format:          str
        """

        super(SysLog, self).__init__(address=address, facility=facility, socktype=socktype)

        self.setLevel(level)

        if format is None:
            format = '[%(asctime)s] %(levelname)-8s: %(message)s [in %(pathname)s:%(lineno)d]'
        self.setFormatter(Formatter(format))
