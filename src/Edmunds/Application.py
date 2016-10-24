
from flask import Flask
from app.Http import routes
from werkzeug.debug import DebuggedApplication
import Edmunds.Support.helpers as helpers


class Application(Flask):
	"""
	The Edmunds Application
	"""

	def __init__(self):
		"""
		Initialize the application
		"""

		super(Application, self).__init__(__name__)

		self.registeredServiceProviders = []

		self.debug = True
		self.wsgi_app = DebuggedApplication(self.wsgi_app, True)

		routes.route(self)


	def register(self, class_):
		"""
		Register a Service Provider
		:param class_: 	The class of the provider
		:type  class_: 	string
		"""

		if class_ not in self.registeredServiceProviders:
			# Only register a provider once
			self.registeredServiceProviders.append(class_)

			serviceProvider = class_(self)
			serviceProvider.register()