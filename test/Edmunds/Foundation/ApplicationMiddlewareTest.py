
from test.TestCase import TestCase
from Edmunds.Foundation.ApplicationMiddleware import ApplicationMiddleware
import Edmunds.Support.helpers as helpers


class ApplicationMiddlewareTest(TestCase):
	"""
	Test the Application Middleware
	"""

	cache = None


	def set_up(self):
		"""
		Set up the test case
		"""

		super(ApplicationMiddlewareTest, self).set_up()

		ApplicationMiddlewareTest.cache = {}


	def test_no_abstract_handle(self):
		"""
		Test if abstract handle method is required
		"""

		with self.assert_raises_regexp(TypeError, 'handle'):
			MyApplicationMiddlewareNoAbstractHandle(self.app)


	def test_abstract_handle(self):
		"""
		Test required abstract handle method
		"""

		self.assert_is_instance(MyApplicationMiddlewareAbstractHandle(self.app), MyApplicationMiddlewareAbstractHandle)


	def test_registering(self):
		"""
		Test registering the application middleware
		"""

		# Check empty
		self.assert_equal(0, self.app._registered_application_middleware.count(MyApplicationMiddleware))

		# Register the middleware
		self.app.middleware(MyApplicationMiddleware)

		# Check if registered
		self.assert_equal(1, self.app._registered_application_middleware.count(MyApplicationMiddleware))
		self.assert_is_instance(self.app.wsgi_app, MyApplicationMiddleware)
		self.assert_not_is_instance(self.app.wsgi_app.wsgi_app, MyApplicationMiddleware)

		# Try adding it again
		self.app.middleware(MyApplicationMiddleware)

		# Check if duplicate
		self.assert_equal(1, self.app._registered_application_middleware.count(MyApplicationMiddleware))
		self.assert_is_instance(self.app.wsgi_app, MyApplicationMiddleware)
		self.assert_not_is_instance(self.app.wsgi_app.wsgi_app, MyApplicationMiddleware)

		# Try adding second one
		self.app.middleware(MySecondApplicationMiddleware)

		# Check if registered
		self.assert_equal(1, self.app._registered_application_middleware.count(MyApplicationMiddleware))
		self.assert_equal(1, self.app._registered_application_middleware.count(MySecondApplicationMiddleware))
		self.assert_is_instance(self.app.wsgi_app, MySecondApplicationMiddleware)
		self.assert_is_instance(self.app.wsgi_app.wsgi_app, MyApplicationMiddleware)
		self.assert_not_is_instance(self.app.wsgi_app.wsgi_app.wsgi_app, MyApplicationMiddleware)


	def test_handling(self):
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

			self.assert_in('handledMiddleware', ApplicationMiddlewareTest.cache)
			self.assert_equal(1, ApplicationMiddlewareTest.cache['handledMiddleware'])

		# Add second middleware
		self.app.middleware(MySecondApplicationMiddleware)

		# Call route
		ApplicationMiddlewareTest.cache = {}
		with self.app.test_client() as c:
			rv = c.get(rule)

			self.assert_in('handledMiddleware', ApplicationMiddlewareTest.cache)
			self.assert_equal(2, ApplicationMiddlewareTest.cache['handledMiddleware'])


	def test_order(self):
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

			self.assert_in('firstHandledMiddleware', ApplicationMiddlewareTest.cache)
			self.assert_equal(MySecondApplicationMiddleware, ApplicationMiddlewareTest.cache['firstHandledMiddleware'])
			self.assert_in('lastHandledMiddleware', ApplicationMiddlewareTest.cache)
			self.assert_equal(MyApplicationMiddleware, ApplicationMiddlewareTest.cache['lastHandledMiddleware'])

		# Register some more
		self.app.middleware(MyApplicationMiddleware)

		# Call route
		ApplicationMiddlewareTest.cache = {}
		with self.app.test_client() as c:
			rv = c.get(rule)

			self.assert_in('firstHandledMiddleware', ApplicationMiddlewareTest.cache)
			self.assert_equal(MySecondApplicationMiddleware, ApplicationMiddlewareTest.cache['firstHandledMiddleware'])
			self.assert_in('lastHandledMiddleware', ApplicationMiddlewareTest.cache)
			self.assert_equal(MyApplicationMiddleware, ApplicationMiddlewareTest.cache['lastHandledMiddleware'])



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
