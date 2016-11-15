
from test.TestCase import TestCase
from Edmunds.Http.RequestMiddleware import RequestMiddleware
import Edmunds.Support.helpers as helpers
from flask import Response


class RequestMiddlewareTest(TestCase):
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
			pass

		# Check middleware empty
		self.assert_not_in(rule, self.app._request_middleware_by_rule)

		# Call route
		RequestMiddlewareTest.cache = {}
		with self.app.test_request_context(rule):
			self.app.preprocess_request()

			self.assert_not_in('handledBefore', RequestMiddlewareTest.cache)
			self.assert_not_in('handledAfter', RequestMiddlewareTest.cache)

			resp = self.app.process_response(Response('...'))

			self.assert_not_in('handledBefore', RequestMiddlewareTest.cache)
			self.assert_not_in('handledAfter', RequestMiddlewareTest.cache)


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
			pass

		# Check middleware
		self.assert_in(rule, self.app._request_middleware_by_rule)
		self.assert_not_in(rule2, self.app._request_middleware_by_rule)
		self.assert_equal(1, len(self.app._request_middleware_by_rule[rule]))
		self.assert_in(MyRequestMiddleware, self.app._request_middleware_by_rule[rule])

		# Call route
		RequestMiddlewareTest.cache = {}
		with self.app.test_request_context(rule):
			self.app.preprocess_request()

			self.assert_in('handledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(1, RequestMiddlewareTest.cache['handledBefore'])
			self.assert_not_in('handledAfter', RequestMiddlewareTest.cache)

			resp = self.app.process_response(Response('...'))

			self.assert_in('handledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(1, RequestMiddlewareTest.cache['handledBefore'])
			self.assert_in('handledAfter', RequestMiddlewareTest.cache)
			self.assert_equal(1, RequestMiddlewareTest.cache['handledAfter'])

		# Add second route
		@self.app.route(rule2, middleware = [ MyRequestMiddleware, MySecondRequestMiddleware ])
		def handleSecondRoute():
			pass

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
		with self.app.test_request_context(rule2):
			self.app.preprocess_request()

			self.assert_in('handledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(2, RequestMiddlewareTest.cache['handledBefore'])
			self.assert_not_in('handledAfter', RequestMiddlewareTest.cache)

			resp = self.app.process_response(Response('...'))

			self.assert_in('handledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(2, RequestMiddlewareTest.cache['handledBefore'])
			self.assert_in('handledAfter', RequestMiddlewareTest.cache)
			self.assert_equal(2, RequestMiddlewareTest.cache['handledAfter'])


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



	def test_order(self):
		"""
		Test the order of the middleware
		"""

		rule = '/' + helpers.random_str(20)
		rule2 = '/' + helpers.random_str(20)
		self.assert_not_equal(rule, rule2)

		# Add route
		@self.app.route(rule, middleware = [ MyRequestMiddleware ])
		def handleRoute():
			pass

		# Call route
		RequestMiddlewareTest.cache = {}
		with self.app.test_request_context(rule):
			self.app.preprocess_request()

			# Check first and last before
			self.assert_in('firstHandledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(MyRequestMiddleware, RequestMiddlewareTest.cache['firstHandledBefore'])
			self.assert_in('lastHandledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(MyRequestMiddleware, RequestMiddlewareTest.cache['lastHandledBefore'])
			# Check first and last after
			self.assert_not_in('firstHandledAfter', RequestMiddlewareTest.cache)
			self.assert_not_in('lastHandledAfter', RequestMiddlewareTest.cache)

			resp = self.app.process_response(Response('...'))

			# Check first and last before
			self.assert_in('firstHandledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(MyRequestMiddleware, RequestMiddlewareTest.cache['firstHandledBefore'])
			self.assert_in('lastHandledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(MyRequestMiddleware, RequestMiddlewareTest.cache['lastHandledBefore'])
			# Check first and last after
			self.assert_in('firstHandledAfter', RequestMiddlewareTest.cache)
			self.assert_equal(MyRequestMiddleware, RequestMiddlewareTest.cache['firstHandledAfter'])
			self.assert_in('lastHandledAfter', RequestMiddlewareTest.cache)
			self.assert_equal(MyRequestMiddleware, RequestMiddlewareTest.cache['lastHandledAfter'])

		# Add second route
		@self.app.route(rule2, middleware = [ MyRequestMiddleware, MySecondRequestMiddleware ])
		def handleSecondRoute():
			pass

		# Call second route
		RequestMiddlewareTest.cache = {}
		with self.app.test_request_context(rule2):
			self.app.preprocess_request()

			# Check first and last before
			self.assert_in('firstHandledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(MyRequestMiddleware, RequestMiddlewareTest.cache['firstHandledBefore'])
			self.assert_in('lastHandledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(MySecondRequestMiddleware, RequestMiddlewareTest.cache['lastHandledBefore'])
			# Check first and last after
			self.assert_not_in('firstHandledAfter', RequestMiddlewareTest.cache)
			self.assert_not_in('lastHandledAfter', RequestMiddlewareTest.cache)

			resp = self.app.process_response(Response('...'))

			# Check first and last before
			self.assert_in('firstHandledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(MyRequestMiddleware, RequestMiddlewareTest.cache['firstHandledBefore'])
			self.assert_in('lastHandledBefore', RequestMiddlewareTest.cache)
			self.assert_equal(MySecondRequestMiddleware, RequestMiddlewareTest.cache['lastHandledBefore'])
			# Check first and last after
			self.assert_in('firstHandledAfter', RequestMiddlewareTest.cache)
			self.assert_equal(MySecondRequestMiddleware, RequestMiddlewareTest.cache['firstHandledAfter'])
			self.assert_in('lastHandledAfter', RequestMiddlewareTest.cache)
			self.assert_equal(MyRequestMiddleware, RequestMiddlewareTest.cache['lastHandledAfter'])



class MyRequestMiddleware(RequestMiddleware):
	"""
	Request Middleware class
	"""

	def before(self):

		if 'handledBefore' not in RequestMiddlewareTest.cache:
			RequestMiddlewareTest.cache['handledBefore'] = 0
		RequestMiddlewareTest.cache['handledBefore'] += 1

		if 'firstHandledBefore' not in RequestMiddlewareTest.cache:
			RequestMiddlewareTest.cache['firstHandledBefore'] = MyRequestMiddleware

		RequestMiddlewareTest.cache['lastHandledBefore'] = MyRequestMiddleware

		return super(MyRequestMiddleware, self).before()


	def after(self, response):

		if 'handledAfter' not in RequestMiddlewareTest.cache:
			RequestMiddlewareTest.cache['handledAfter'] = 0
		RequestMiddlewareTest.cache['handledAfter'] += 1

		if 'firstHandledAfter' not in RequestMiddlewareTest.cache:
			RequestMiddlewareTest.cache['firstHandledAfter'] = MyRequestMiddleware

		RequestMiddlewareTest.cache['lastHandledAfter'] = MyRequestMiddleware

		return super(MyRequestMiddleware, self).after(response)



class MySecondRequestMiddleware(RequestMiddleware):
	"""
	Second Request Middleware class
	"""

	def before(self):

		if 'handledBefore' not in RequestMiddlewareTest.cache:
			RequestMiddlewareTest.cache['handledBefore'] = 0
		RequestMiddlewareTest.cache['handledBefore'] += 1

		if 'firstHandledBefore' not in RequestMiddlewareTest.cache:
			RequestMiddlewareTest.cache['firstHandledBefore'] = MySecondRequestMiddleware

		RequestMiddlewareTest.cache['lastHandledBefore'] = MySecondRequestMiddleware

		return super(MySecondRequestMiddleware, self).before()


	def after(self, response):

		if 'handledAfter' not in RequestMiddlewareTest.cache:
			RequestMiddlewareTest.cache['handledAfter'] = 0
		RequestMiddlewareTest.cache['handledAfter'] += 1

		if 'firstHandledAfter' not in RequestMiddlewareTest.cache:
			RequestMiddlewareTest.cache['firstHandledAfter'] = MySecondRequestMiddleware

		RequestMiddlewareTest.cache['lastHandledAfter'] = MySecondRequestMiddleware

		return super(MySecondRequestMiddleware, self).after(response)