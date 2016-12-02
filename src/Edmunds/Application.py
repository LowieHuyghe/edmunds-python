
from flask import Flask
from Edmunds.Foundation.Concerns.Config import Config as ConcernsConfig
from Edmunds.Foundation.Concerns.RuntimeEnvironment import RuntimeEnvironment as ConcernsRuntimeEnvironment
from Edmunds.Foundation.Concerns.ServiceProviders import ServiceProviders as ConcernsServiceProviders
from Edmunds.Foundation.Concerns.Middleware import Middleware as ConcernsMiddleware
from Edmunds.Foundation.Concerns.RequestRouting import RequestRouting as ConcernsRequestRouting
from Edmunds.Foundation.Concerns.Storage import Storage as ConcernsStorage
from Edmunds.Exceptions.ExceptionsServiceProvider import ExceptionsServiceProvider
from Edmunds.Config.Config import Config
from app.Http import routes


class Application(Flask, ConcernsConfig, ConcernsRuntimeEnvironment, ConcernsServiceProviders, ConcernsMiddleware, ConcernsRequestRouting, ConcernsStorage):
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
		self._init_runtime_environment()

		self.register(ExceptionsServiceProvider)

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

		# Pre handline
		self._pre_handle_route_dispatching(rule, options)
		self._pre_handle_route_middleware(rule, options)

		# Fetch the decorator function
		decorator = super(Application, self).route(rule, **options)

		# Post handline
		decorator = self._post_handle_route_middleware(decorator, rule, options)
		decorator = self._post_handle_route_dispatching(decorator, rule, options)

		return decorator
