
from flask import Flask
from Edmunds.Foundation.Concerns.ServiceProviders import ServiceProviders as ConcernsServiceProviders
from Edmunds.Foundation.Concerns.Middleware import Middleware as ConcernsMiddleware
from werkzeug.debug import DebuggedApplication
from app.Http import routes
from Edmunds.Config.Config import Config


class Application(Flask, ConcernsServiceProviders, ConcernsMiddleware):
	"""
	The Edmunds Application
	"""

	config_class = Config


	def __init__(self, import_name):
		"""
		Initialize the application
		:param import_name: 	Import name
		:type  import_name: 	str
		"""

		super(Application, self).__init__(import_name)

		self.debug = True
		self.wsgi_app = DebuggedApplication(self.wsgi_app, True)

		self._init_service_providers()
		self._init_middleware()

		routes.route(self)


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

		# handle request middleware
		middleware = options.pop('middleware', [])
		self._handle_route_request_middleware(rule, middleware)

		return super(Application, self).route(rule, **options)
