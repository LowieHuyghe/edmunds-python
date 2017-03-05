
try:
	from SocketServer import BaseRequestHandler, UDPServer
except ImportError:
	from socketserver import BaseRequestHandler, UDPServer
import threading
from time import sleep


class SysLogServer(object):

	def __init__(self, host = '0.0.0.0', port = 12323):
		"""
		Initiate the server object
		:param host:	The host
		:type  host:	str
		:param port:	The port
		:type  port:	int
		"""

		self.host = host
		self.port = port

		self._server = None
		self._thread = None
		self._data = []


	def start(self):
		"""
		Start the server
		"""

		if self._thread:
			return False

		self._data = []

		self._server = SysLogUDPServer((self.host, self.port), SysLogServerHandler)
		self._thread = threading.Thread(target = self._server.serve_forever)
		self._thread.start()

		return True


	def stop(self):
		"""
		Stop the server
		"""

		if not self._thread:
			return False

		sleep(0.01)
		self._data = self._server.data

		self._server.shutdown()
		self._thread = None
		self._server = None

		return True


	def get_data(self):
		"""
		Get the data
		"""

		if self._server:
			sleep(0.01)
			return self._server.data
		else:
			return self._data



class SysLogUDPServer(UDPServer):

	def __init__(self, *args, **kwargs):
		"""
		Initiate the server
		"""

		UDPServer.__init__(self, *args, **kwargs)

		self.data = []



class SysLogServerHandler(BaseRequestHandler):

	def handle(self):
		"""
		Handle the incoming syslog data
		"""

		data = str(bytes.decode(self.request[0].strip()))
		self.server.data.append(data)
