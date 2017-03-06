
from logging.handlers import SysLogHandler, SYSLOG_UDP_PORT
from logging import WARNING
from socket import SOCK_DGRAM


class SysLog(SysLogHandler):
    """
    Sys Log Driver
    """

    def __init__(self, app, address=('localhost', SYSLOG_UDP_PORT), facility=SysLogHandler.LOG_USER, socktype=SOCK_DGRAM, level=WARNING):
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
        """

        super(SysLog, self).__init__(address=address, facility=facility, socktype=socktype)

        self.setLevel(level)
