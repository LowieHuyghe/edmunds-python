
import abc


class ApplicationMiddleware(object):
	"""
	The Application Middleware
	"""

	__metaclass__ = abc.ABCMeta


	def __init__(self, app):
		"""
		Initialize the application
		:param app: 	The application
		:type  app: 	Edmunds.Application
		"""

		self.app = app
		self.wsgi_app = app.wsgi_app


	def __call__(self, environment, startResponse):
		"""
		Incoming call of middleware
		:param environment: 	The environment
		:type  environment: 	Edmunds.Application
		:param startResponse: 	The application
		:type  startResponse: 	Edmunds.Application
		"""

		return self.handle(environment, startResponse)


	@abc.abstractmethod
	def handle(self, environment, startResponse):
		"""
		Handle the middleware
		:param environment: 	The environment
		:type  environment: 	Edmunds.Application
		:param startResponse: 	The application
		:type  startResponse: 	Edmunds.Application
		"""

		return self.wsgi_app(environment, startResponse)