
from test.TestCase import TestCase
from Edmunds.Foundation.ApplicationMiddleware import ApplicationMiddleware
import Edmunds.Support.helpers as helpers
from flask import g


class ApplicationMiddlewareTest(TestCase):
	"""
	Test the Application Middleware
	"""

	def testNoAbstractHandle(self):
		"""
		Test if abstract handle method is required
		"""

		try:
			MyApplicationMiddlewareNoAbstractHandle(self.app)
			assert False

		except TypeError as e:
			if 'handle' in e.message:
				assert True
			else:
				raise e


	def testAbstractHandle(self):
		"""
		Test required abstract handle method
		"""

		MyApplicationMiddlewareAbstractHandle(self.app)
		assert True


	def testRegistering(self):
		"""
		Test registering the application middleware
		"""

		# Register the middleware
		self.app.middleware(MyApplicationMiddlewareAbstractHandle)

		# Check if registered
		assert 1 == self.app._registered_application_middleware.count(MyApplicationMiddlewareAbstractHandle)
		assert isinstance(self.app.wsgi_app, MyApplicationMiddlewareAbstractHandle)

		# Try adding it again
		self.app.middleware(MyApplicationMiddlewareAbstractHandle)

		# Check if duplicate
		assert 1 == self.app._registered_application_middleware.count(MyApplicationMiddlewareAbstractHandle)
		assert isinstance(self.app.wsgi_app, MyApplicationMiddlewareAbstractHandle)
		assert not isinstance(self.app.wsgi_app.wsgi_app, MyApplicationMiddlewareAbstractHandle)


	def testHandling(self):
		"""
		Test handling of application middleware
		"""

		# Register the middleware
		self.app.middleware(MyApplicationMiddlewareAbstractHandle)

		# Add route
		rule = '/' + helpers.random_str(20)
		@self.app.route(rule)
		def handleRoute():
			g.handledRoute = True
			return 'handledRoute'

		# Call route
		with self.app.test_client() as c:
			rv = c.get(rule)

			assert 'handledRoute' in g
			assert g.handledRoute

			assert 'handledMiddleware' in g
			assert g.handledMiddleware



class MyApplicationMiddlewareNoAbstractHandle(ApplicationMiddleware):
	"""
	Application Middleware class with missing handle method
	"""

	pass


class MyApplicationMiddlewareAbstractHandle(ApplicationMiddleware):
	"""
	Application Middleware class with handle method
	"""

	def handle(self, environment, startResponse):

		@self.app.before_request
		def before_request():
			g.handledMiddleware = True

		return super(MyApplicationMiddlewareAbstractHandle, self).handle(environment, startResponse)