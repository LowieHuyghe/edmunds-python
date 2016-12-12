
from logging.handlers import SysLogHandler, SYSLOG_UDP_PORT
from logging import WARNING
from syslog import LOG_USER


class SysLog(SysLogHandler):
	"""
	Sys Log Driver
	"""

	def __init__(self, app, host = 'localhost', port = SYSLOG_UDP_PORT, facility = LOG_USER, level = WARNING):
		"""
		Initiate the instance
		:param app: 			The application
		:type  app: 			Edmunds.Application
		:param host:			The host
		:type  host:			str
		:param port: 			The port
		:type  port: 			int
		:param facility: 		The facility to log
		:type  facility: 		int
		:param level: 			The minimum level to log
		:type  level: 			int
		"""

		super(SysLog, self).__init__(address = (host, port), facility = facility)

		self.setLevel(level)