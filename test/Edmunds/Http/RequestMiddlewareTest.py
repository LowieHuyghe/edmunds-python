
from test.TestCase import TestCase
from Edmunds.Http.RequestMiddleware import RequestMiddleware
import Edmunds.Support.helpers as helpers
from flask import Response


class RequestMiddlewareTest(TestCase):
	"""
	Test the Request Middleware
	"""

	cache = None


	def setUp(self):
		"""
		Set up the test case
		"""

		super(RequestMiddlewareTest, self).setUp()

		RequestMiddlewareTest.cache = {}


	def testRegistering(self):
		"""
		Test registering the request middleware
		"""

		rule = '/' + helpers.random_str(20)
		rule2 = '/' + helpers.random_str(20)
		assert rule != rule2

		# Check empty
		assert rule not in self.app._request_middleware_by_rule
		assert rule2 not in self.app._request_middleware_by_rule

		# Add route
		@self.app.route(rule, middleware = [ MyRequestMiddleware ])
		def handleRoute():
			pass

		# Check middleware
		assert rule in self.app._request_middleware_by_rule
		assert rule2 not in self.app._request_middleware_by_rule
		assert 1 == len(self.app._request_middleware_by_rule[rule])
		assert MyRequestMiddleware in self.app._request_middleware_by_rule[rule]

		# Call route
		RequestMiddlewareTest.cache = {}
		with self.app.test_request_context(rule):
			self.app.preprocess_request()

			assert 'handledBefore' in RequestMiddlewareTest.cache
			assert 1 == RequestMiddlewareTest.cache['handledBefore']
			assert 'handledAfter' not in RequestMiddlewareTest.cache

			resp = self.app.process_response(Response('...'))

			assert 'handledBefore' in RequestMiddlewareTest.cache
			assert 1 == RequestMiddlewareTest.cache['handledBefore']
			assert 'handledAfter' in RequestMiddlewareTest.cache
			assert 1 == RequestMiddlewareTest.cache['handledAfter']

		# Add second route
		@self.app.route(rule2, middleware = [ MyRequestMiddleware, MySecondRequestMiddleware ])
		def handleSecondRoute():
			pass

		# Check middleware
		assert rule in self.app._request_middleware_by_rule
		assert rule2 in self.app._request_middleware_by_rule
		assert 1 == len(self.app._request_middleware_by_rule[rule])
		assert 2 == len(self.app._request_middleware_by_rule[rule2])
		assert MyRequestMiddleware in self.app._request_middleware_by_rule[rule]
		assert MyRequestMiddleware in self.app._request_middleware_by_rule[rule2]
		assert MySecondRequestMiddleware in self.app._request_middleware_by_rule[rule2]

		# Call route
		RequestMiddlewareTest.cache = {}
		with self.app.test_request_context(rule2):
			self.app.preprocess_request()

			assert 'handledBefore' in RequestMiddlewareTest.cache
			assert 2 == RequestMiddlewareTest.cache['handledBefore']
			assert 'handledAfter' not in RequestMiddlewareTest.cache

			resp = self.app.process_response(Response('...'))

			assert 'handledBefore' in RequestMiddlewareTest.cache
			assert 2 == RequestMiddlewareTest.cache['handledBefore']
			assert 'handledAfter' in RequestMiddlewareTest.cache
			assert 2 == RequestMiddlewareTest.cache['handledAfter']


	def testOverwriting(self):
		"""
		Test overwriting of middleware
		"""

		rule = '/' + helpers.random_str(20)

		# Check empty
		assert rule not in self.app._request_middleware_by_rule

		# Add route
		@self.app.route(rule, middleware = [ MyRequestMiddleware ])
		def handleRoute():
			pass

		# Check middleware
		assert rule in self.app._request_middleware_by_rule
		assert 1 == len(self.app._request_middleware_by_rule[rule])

		# Overwrite route
		@self.app.route(rule, middleware = [ MyRequestMiddleware, MySecondRequestMiddleware ])
		def handleOverwrittenRoute():
			pass

		# Check middleware
		assert rule in self.app._request_middleware_by_rule
		assert 2 == len(self.app._request_middleware_by_rule[rule])



	def testOrder(self):
		"""
		Test the order of the middleware
		"""

		rule = '/' + helpers.random_str(20)
		rule2 = '/' + helpers.random_str(20)
		assert rule != rule2

		# Add route
		@self.app.route(rule, middleware = [ MyRequestMiddleware ])
		def handleRoute():
			pass

		# Call route
		RequestMiddlewareTest.cache = {}
		with self.app.test_request_context(rule):
			self.app.preprocess_request()

			# Check first and last before
			assert 'firstHandledBefore' in RequestMiddlewareTest.cache
			assert MyRequestMiddleware == RequestMiddlewareTest.cache['firstHandledBefore']
			assert 'lastHandledBefore' in RequestMiddlewareTest.cache
			assert MyRequestMiddleware == RequestMiddlewareTest.cache['lastHandledBefore']
			# Check first and last after
			assert 'firstHandledAfter' not in RequestMiddlewareTest.cache
			assert 'lastHandledAfter' not in RequestMiddlewareTest.cache

			resp = self.app.process_response(Response('...'))

			# Check first and last before
			assert 'firstHandledBefore' in RequestMiddlewareTest.cache
			assert MyRequestMiddleware == RequestMiddlewareTest.cache['firstHandledBefore']
			assert 'lastHandledBefore' in RequestMiddlewareTest.cache
			assert MyRequestMiddleware == RequestMiddlewareTest.cache['lastHandledBefore']
			# Check first and last after
			assert 'firstHandledAfter' in RequestMiddlewareTest.cache
			assert MyRequestMiddleware == RequestMiddlewareTest.cache['firstHandledAfter']
			assert 'lastHandledAfter' in RequestMiddlewareTest.cache
			assert MyRequestMiddleware == RequestMiddlewareTest.cache['lastHandledAfter']

		# Add second route
		@self.app.route(rule2, middleware = [ MyRequestMiddleware, MySecondRequestMiddleware ])
		def handleSecondRoute():
			pass

		# Call second route
		RequestMiddlewareTest.cache = {}
		with self.app.test_request_context(rule2):
			self.app.preprocess_request()

			# Check first and last before
			assert 'firstHandledBefore' in RequestMiddlewareTest.cache
			assert MyRequestMiddleware == RequestMiddlewareTest.cache['firstHandledBefore']
			assert 'lastHandledBefore' in RequestMiddlewareTest.cache
			assert MySecondRequestMiddleware == RequestMiddlewareTest.cache['lastHandledBefore']
			# Check first and last after
			assert 'firstHandledAfter' not in RequestMiddlewareTest.cache
			assert 'lastHandledAfter' not in RequestMiddlewareTest.cache

			resp = self.app.process_response(Response('...'))

			# Check first and last before
			assert 'firstHandledBefore' in RequestMiddlewareTest.cache
			assert MyRequestMiddleware == RequestMiddlewareTest.cache['firstHandledBefore']
			assert 'lastHandledBefore' in RequestMiddlewareTest.cache
			assert MySecondRequestMiddleware == RequestMiddlewareTest.cache['lastHandledBefore']
			# Check first and last after
			assert 'firstHandledAfter' in RequestMiddlewareTest.cache
			assert MySecondRequestMiddleware == RequestMiddlewareTest.cache['firstHandledAfter']
			assert 'lastHandledAfter' in RequestMiddlewareTest.cache
			assert MyRequestMiddleware == RequestMiddlewareTest.cache['lastHandledAfter']



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