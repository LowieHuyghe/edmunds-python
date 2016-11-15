
from flask import Flask
from Edmunds.Foundation.Concerns.RuntimeEnvironment import RuntimeEnvironment as ConcernsRuntimeEnvironment
from Edmunds.Foundation.Concerns.ServiceProviders import ServiceProviders as ConcernsServiceProviders
from Edmunds.Foundation.Concerns.Middleware import Middleware as ConcernsMiddleware
from Edmunds.Foundation.Concerns.RequestRouting import RequestRouting as ConcernsRequestRouting
from werkzeug.debug import DebuggedApplication
from app.Http import routes
from Edmunds.Config.Config import Config


class Application(Flask, ConcernsRuntimeEnvironment, ConcernsServiceProviders, ConcernsMiddleware, ConcernsRequestRouting):
	"""
	The Edmunds Application
	"""

	config_class = Config


	def __init__(self, import_name, config_dirs = None):
		"""
		Initialize the application
		:param import_name: 	Import name
		:type  import_name: 	str
		:param config_dirs: 	Configuration directories
		:type  config_dirs: 	list
		"""

		super(Application, self).__init__(import_name)

		self._init_config(config_dirs)
		self._init_service_providers()
		self._init_middleware()
		self._init_request_routing()

		routes.route(self)


	def _init_config(self, config_dirs = None):
		"""
		Initiate the configuration
		:param config_dirs: 	Configuration directories
		:type  config_dirs: 	list
		"""

		# Configuration directories
		if config_dirs == None:
			config_dirs = [
				'lib/edmunds/src/config',
				'config',
			]

		# Load config
		self.config.load_all(config_dirs)

		# Set to debug mode
		if self.config('app.debug'):
			self.debug = True
			self.wsgi_app = DebuggedApplication(self.wsgi_app, True)

		# Testing environment
		if self.is_testing():
			self.testing = True


	def route(self, rule, **options):
		"""
		Register a route
		This is merely a step to abstract the middleware from the route
		:param rule: 	The rule for routing the request
		:type  rule: 	str
		:param options: List of options
		:type  options: list
		:return: 		Decorator function
		:rtype: 		function
		"""

		# Fetch custom options
		middleware = options.pop('middleware', None)
		uses = options.pop('uses', None)

		# Fetch the decorator function
		decorator = super(Application, self).route(rule, **options)

		# Handle middleware
		decorator = self._handle_route_request_middleware(decorator, rule, middleware)

		# Handle custom dispatching
		decorator = self._handle_route_request_dispatching(decorator, rule, uses)

		return decorator
