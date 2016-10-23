
from flask import Flask
from app.Http import routes
from werkzeug.debug import DebuggedApplication
import Support.helpers as helpers


class Application(Flask):
	"""
	The Edmunds Application
	"""

	def __init__(self):
		"""
		Initialize the application
		"""

		super(Application, self).__init__(__name__)

		self.debug = True
		self.wsgi_app = DebuggedApplication(self.wsgi_app, True)

		routes.route(self)


	def register(self, className):
		"""
		Register a Service Provider
		:param className: 	The class name of the provider
		:type  className: 	string
		"""

		serviceProviderClass = helpers.getClass(className)

		serviceProvider = serviceProviderClass(self)
		serviceProvider.register()