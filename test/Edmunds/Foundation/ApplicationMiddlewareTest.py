
from test.TestCase import TestCase
from Edmunds.Foundation.ApplicationMiddleware import ApplicationMiddleware
import Edmunds.Support.helpers as helpers


class ApplicationMiddlewareTest(TestCase):
	"""
	Test the Application Middleware
	"""

	cache = None


	def setUp(self):
		"""
		Set up the test case
		"""

		super(ApplicationMiddlewareTest, self).setUp()

		ApplicationMiddlewareTest.cache = {}


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

		# Check empty
		assert 0 == self.app._registered_application_middleware.count(MyApplicationMiddleware)

		# Register the middleware
		self.app.middleware(MyApplicationMiddleware)

		# Check if registered
		assert 1 == self.app._registered_application_middleware.count(MyApplicationMiddleware)
		assert isinstance(self.app.wsgi_app, MyApplicationMiddleware)
		assert not isinstance(self.app.wsgi_app.wsgi_app, MyApplicationMiddleware)

		# Try adding it again
		self.app.middleware(MyApplicationMiddleware)

		# Check if duplicate
		assert 1 == self.app._registered_application_middleware.count(MyApplicationMiddleware)
		assert isinstance(self.app.wsgi_app, MyApplicationMiddleware)
		assert not isinstance(self.app.wsgi_app.wsgi_app, MyApplicationMiddleware)

		# Try adding second one
		self.app.middleware(MySecondApplicationMiddleware)

		# Check if registered
		assert 1 == self.app._registered_application_middleware.count(MyApplicationMiddleware)
		assert 1 == self.app._registered_application_middleware.count(MySecondApplicationMiddleware)
		assert isinstance(self.app.wsgi_app, MySecondApplicationMiddleware)
		assert isinstance(self.app.wsgi_app.wsgi_app, MyApplicationMiddleware)
		assert not isinstance(self.app.wsgi_app.wsgi_app.wsgi_app, MyApplicationMiddleware)


	def testHandling(self):
		"""
		Test handling of application middleware
		"""

		# Register the middleware
		self.app.middleware(MyApplicationMiddleware)
		# Add it a second time to make sure it is only called once
		self.app.middleware(MyApplicationMiddleware)

		# Add route
		rule = '/' + helpers.random_str(20)
		@self.app.route(rule)
		def handleRoute():
			pass

		# Call route
		ApplicationMiddlewareTest.cache = {}
		with self.app.test_client() as c:
			rv = c.get(rule)

			assert 'handledMiddleware' in ApplicationMiddlewareTest.cache
			assert 1 == ApplicationMiddlewareTest.cache['handledMiddleware']

		# Add second middleware
		self.app.middleware(MySecondApplicationMiddleware)

		# Call route
		ApplicationMiddlewareTest.cache = {}
		with self.app.test_client() as c:
			rv = c.get(rule)

			assert 'handledMiddleware' in ApplicationMiddlewareTest.cache
			assert 2 == ApplicationMiddlewareTest.cache['handledMiddleware']


	def testOrder(self):
		"""
		Test order of middleware
		"""

		# Register the middleware
		self.app.middleware(MyApplicationMiddleware)
		self.app.middleware(MySecondApplicationMiddleware)

		# Add route
		rule = '/' + helpers.random_str(20)
		@self.app.route(rule)
		def handleRoute():
			pass

		# Call route
		ApplicationMiddlewareTest.cache = {}
		with self.app.test_client() as c:
			rv = c.get(rule)

			assert 'firstHandledMiddleware' in ApplicationMiddlewareTest.cache
			assert MySecondApplicationMiddleware == ApplicationMiddlewareTest.cache['firstHandledMiddleware']
			assert 'lastHandledMiddleware' in ApplicationMiddlewareTest.cache
			assert MyApplicationMiddleware == ApplicationMiddlewareTest.cache['lastHandledMiddleware']

		# Register some more
		self.app.middleware(MyApplicationMiddleware)

		# Call route
		ApplicationMiddlewareTest.cache = {}
		with self.app.test_client() as c:
			rv = c.get(rule)

			assert 'firstHandledMiddleware' in ApplicationMiddlewareTest.cache
			assert MySecondApplicationMiddleware == ApplicationMiddlewareTest.cache['firstHandledMiddleware']
			assert 'lastHandledMiddleware' in ApplicationMiddlewareTest.cache
			assert MyApplicationMiddleware == ApplicationMiddlewareTest.cache['lastHandledMiddleware']



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



class MyApplicationMiddleware(ApplicationMiddleware):
	"""
	Application Middleware class
	"""

	def handle(self, environment, startResponse):

		if 'handledMiddleware' not in ApplicationMiddlewareTest.cache:
			ApplicationMiddlewareTest.cache['handledMiddleware'] = 0
		ApplicationMiddlewareTest.cache['handledMiddleware'] += 1

		if 'firstHandledMiddleware' not in ApplicationMiddlewareTest.cache:
			ApplicationMiddlewareTest.cache['firstHandledMiddleware'] = MyApplicationMiddleware

		ApplicationMiddlewareTest.cache['lastHandledMiddleware'] = MyApplicationMiddleware

		return super(MyApplicationMiddleware, self).handle(environment, startResponse)



class MySecondApplicationMiddleware(ApplicationMiddleware):
	"""
	Second Application Middleware class
	"""

	def handle(self, environment, startResponse):

		if 'handledMiddleware' not in ApplicationMiddlewareTest.cache:
			ApplicationMiddlewareTest.cache['handledMiddleware'] = 0
		ApplicationMiddlewareTest.cache['handledMiddleware'] += 1

		if 'firstHandledMiddleware' not in ApplicationMiddlewareTest.cache:
			ApplicationMiddlewareTest.cache['firstHandledMiddleware'] = MySecondApplicationMiddleware

		ApplicationMiddlewareTest.cache['lastHandledMiddleware'] = MySecondApplicationMiddleware

		return super(MySecondApplicationMiddleware, self).handle(environment, startResponse)
