
from test.TestCase import TestCase
from Edmunds.Foundation.ApplicationMiddleware import ApplicationMiddleware


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
		pass