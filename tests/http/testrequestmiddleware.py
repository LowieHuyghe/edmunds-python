
from test.TestCase import TestCase
from edmunds.http.requestmiddleware import RequestMiddleware
import edmunds.support.helpers as helpers
from flask import Response


class TestRequestMiddleware(TestCase):
	"""
	Test the Request Middleware
	"""

	cache = None


	def set_up(self):
		"""
		Set up the test case
		"""

		super(RequestMiddlewareTest, self).set_up()

		RequestMiddlewareTest.cache = {}
		RequestMiddlewareTest.cache['timeline'] = []


	def test_no_middleware(self):
		"""
		Test route with no middleware
		"""

		rule = '/' + helpers.random_str(20)

		# Check empty
		self.assert_not_in(rule, self.app._request_middleware_by_rule)

		# Add route
		@self.app.route(rule)
		def handleRoute():
			RequestMiddlewareTest.cache['timeline'].append('handleRoute')
			return ''

		# Check middleware empty
		self.assert_not_in(rule, self.app._request_middleware_by_rule)

		# Call route
		with self.app.test_client() as c:
			c.get(rule)

			self.assert_equal(1, len(RequestMiddlewareTest.cache['timeline']))

			self.assert_in('handleRoute', RequestMiddlewareTest.cache['timeline'])
			self.assert_equal(0, RequestMiddlewareTest.cache['timeline'].index('handleRoute'))


	def test_registering(self):
		"""
		Test registering the request middleware
		"""

		rule = '/' + helpers.random_str(20)
		rule2 = '/' + helpers.random_str(20)
		self.assert_not_equal(rule, rule2)

		# Check empty
		self.assert_not_in(rule, self.app._request_middleware_by_rule)
		self.assert_not_in(rule2, self.app._request_middleware_by_rule)

		# Add route
		@self.app.route(rule, middleware = [ MyRequestMiddleware ])
		def handleRoute():
			RequestMiddlewareTest.cache['timeline'].append('handleRoute')
			return ''

		# Check middleware
		self.assert_in(rule, self.app._request_middleware_by_rule)
		self.assert_not_in(rule2, self.app._request_middleware_by_rule)
		self.assert_equal(1, len(self.app._request_middleware_by_rule[rule]))
		self.assert_in(MyRequestMiddleware, self.app._request_middleware_by_rule[rule])

		# Call route
		with self.app.test_request_context(rule):
			self.app.preprocess_request()
			rv = self.app.dispatch_request()
			response = self.app.make_response(rv)
			response = self.app.process_response(response)

			self.assert_equal(3, len(RequestMiddlewareTest.cache['timeline']))

			self.assert_in(MyRequestMiddleware.__name__ + '.before', RequestMiddlewareTest.cache['timeline'])
			self.assert_equal(0, RequestMiddlewareTest.cache['timeline'].index(MyRequestMiddleware.__name__ + '.before'))

			self.assert_in('handleRoute', RequestMiddlewareTest.cache['timeline'])
			self.assert_equal(1, RequestMiddlewareTest.cache['timeline'].index('handleRoute'))

			self.assert_in(MyRequestMiddleware.__name__ + '.after', RequestMiddlewareTest.cache['timeline'])
			self.assert_equal(2, RequestMiddlewareTest.cache['timeline'].index(MyRequestMiddleware.__name__ + '.after'))


		# Add second route
		@self.app.route(rule2, middleware = [ MyRequestMiddleware, MySecondRequestMiddleware ])
		def handleSecondRoute():
			RequestMiddlewareTest.cache['timeline'].append('handleRoute')
			return ''

		# Check middleware
		self.assert_in(rule, self.app._request_middleware_by_rule)
		self.assert_in(rule2, self.app._request_middleware_by_rule)
		self.assert_equal(1, len(self.app._request_middleware_by_rule[rule]))
		self.assert_equal(2, len(self.app._request_middleware_by_rule[rule2]))
		self.assert_in(MyRequestMiddleware, self.app._request_middleware_by_rule[rule])
		self.assert_in(MyRequestMiddleware, self.app._request_middleware_by_rule[rule2])
		self.assert_in(MySecondRequestMiddleware, self.app._request_middleware_by_rule[rule2])

		# Call route
		RequestMiddlewareTest.cache = {}
		RequestMiddlewareTest.cache['timeline'] = []
		with self.app.test_request_context(rule2):
			self.app.preprocess_request()
			rv = self.app.dispatch_request()
			response = self.app.make_response(rv)
			response = self.app.process_response(response)

			self.assert_equal(5, len(RequestMiddlewareTest.cache['timeline']))

			self.assert_in(MyRequestMiddleware.__name__ + '.before', RequestMiddlewareTest.cache['timeline'])
			self.assert_equal(0, RequestMiddlewareTest.cache['timeline'].index(MyRequestMiddleware.__name__ + '.before'))

			self.assert_in(MySecondRequestMiddleware.__name__ + '.before', RequestMiddlewareTest.cache['timeline'])
			self.assert_equal(1, RequestMiddlewareTest.cache['timeline'].index(MySecondRequestMiddleware.__name__ + '.before'))

			self.assert_in('handleRoute', RequestMiddlewareTest.cache['timeline'])
			self.assert_equal(2, RequestMiddlewareTest.cache['timeline'].index('handleRoute'))

			self.assert_in(MySecondRequestMiddleware.__name__ + '.after', RequestMiddlewareTest.cache['timeline'])
			self.assert_equal(3, RequestMiddlewareTest.cache['timeline'].index(MySecondRequestMiddleware.__name__ + '.after'))

			self.assert_in(MyRequestMiddleware.__name__ + '.after', RequestMiddlewareTest.cache['timeline'])
			self.assert_equal(4, RequestMiddlewareTest.cache['timeline'].index(MyRequestMiddleware.__name__ + '.after'))


	def test_overwriting(self):
		"""
		Test overwriting of middleware
		"""

		rule = '/' + helpers.random_str(20)

		# Check empty
		self.assert_not_in(rule, self.app._request_middleware_by_rule)

		# Add route
		@self.app.route(rule, middleware = [ MyRequestMiddleware ])
		def handleRoute():
			pass

		# Check middleware
		self.assert_in(rule, self.app._request_middleware_by_rule)
		self.assert_equal(1, len(self.app._request_middleware_by_rule[rule]))

		# Overwrite route
		@self.app.route(rule, middleware = [ MyRequestMiddleware, MySecondRequestMiddleware ])
		def handleOverwrittenRoute():
			pass

		# Check middleware
		self.assert_in(rule, self.app._request_middleware_by_rule)
		self.assert_equal(2, len(self.app._request_middleware_by_rule[rule]))



class MyRequestMiddleware(RequestMiddleware):
	"""
	Request Middleware class
	"""

	def before(self):

		RequestMiddlewareTest.cache['timeline'].append(self.__class__.__name__ + '.before')

		return super(MyRequestMiddleware, self).before()


	def after(self, response):

		RequestMiddlewareTest.cache['timeline'].append(self.__class__.__name__ + '.after')

		return super(MyRequestMiddleware, self).after(response)



class MySecondRequestMiddleware(RequestMiddleware):
	"""
	Second Request Middleware class
	"""

	def before(self):

		RequestMiddlewareTest.cache['timeline'].append(self.__class__.__name__ + '.before')

		return super(MySecondRequestMiddleware, self).before()


	def after(self, response):

		RequestMiddlewareTest.cache['timeline'].append(self.__class__.__name__ + '.after')

		return super(MySecondRequestMiddleware, self).after(response)