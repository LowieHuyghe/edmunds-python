
from test.TestCase import TestCase
from Edmunds.Http.RequestMiddleware import RequestMiddleware
import Edmunds.Support.helpers as helpers
from flask import g, Response


class RequestMiddlewareTest(TestCase):
	"""
	Test the Request Middleware
	"""

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
		with self.app.test_request_context(rule):
			self.app.preprocess_request()

			assert 'handledBefore' in g
			assert 1 == g.handledBefore
			assert 'handledAfter' not in g

			resp = self.app.process_response(Response('...'))

			assert 'handledBefore' in g
			assert 1 == g.handledBefore
			assert 'handledAfter' in g
			assert 1 == g.handledAfter

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
		with self.app.test_request_context(rule2):
			self.app.preprocess_request()

			assert 'handledBefore' in g
			assert 2 == g.handledBefore
			assert 'handledAfter' not in g

			resp = self.app.process_response(Response('...'))

			assert 'handledBefore' in g
			assert 2 == g.handledBefore
			assert 'handledAfter' in g
			assert 2 == g.handledAfter


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
		with self.app.test_request_context(rule):
			self.app.preprocess_request()

			# Check first and last before
			assert 'firstHandledBefore' in g
			assert MyRequestMiddleware == g.firstHandledBefore
			assert 'lastHandledBefore' in g
			assert MyRequestMiddleware == g.lastHandledBefore
			# Check first and last after
			assert 'firstHandledAfter' not in g
			assert 'lastHandledAfter' not in g

			resp = self.app.process_response(Response('...'))

			# Check first and last before
			assert 'firstHandledBefore' in g
			assert MyRequestMiddleware == g.firstHandledBefore
			assert 'lastHandledBefore' in g
			assert MyRequestMiddleware == g.lastHandledBefore
			# Check first and last after
			assert 'firstHandledAfter' in g
			assert MyRequestMiddleware == g.firstHandledAfter
			assert 'lastHandledAfter' in g
			assert MyRequestMiddleware == g.lastHandledAfter

		# Add second route
		@self.app.route(rule2, middleware = [ MyRequestMiddleware, MySecondRequestMiddleware ])
		def handleSecondRoute():
			pass

		# Call second route
		with self.app.test_request_context(rule2):
			self.app.preprocess_request()

			# Check first and last before
			assert 'firstHandledBefore' in g
			assert MyRequestMiddleware == g.firstHandledBefore
			assert 'lastHandledBefore' in g
			assert MySecondRequestMiddleware == g.lastHandledBefore
			# Check first and last after
			assert 'firstHandledAfter' not in g
			assert 'lastHandledAfter' not in g

			resp = self.app.process_response(Response('...'))

			# Check first and last before
			assert 'firstHandledBefore' in g
			assert MyRequestMiddleware == g.firstHandledBefore
			assert 'lastHandledBefore' in g
			assert MySecondRequestMiddleware == g.lastHandledBefore
			# Check first and last after
			assert 'firstHandledAfter' in g
			assert MySecondRequestMiddleware == g.firstHandledAfter
			assert 'lastHandledAfter' in g
			assert MyRequestMiddleware == g.lastHandledAfter



class MyRequestMiddleware(RequestMiddleware):
	"""
	Request Middleware class
	"""

	def before(self):

		if 'handledBefore' not in g:
			g.handledBefore = 0
		g.handledBefore += 1

		if 'firstHandledBefore' not in g:
			g.firstHandledBefore = MyRequestMiddleware

		g.lastHandledBefore = MyRequestMiddleware

		return super(MyRequestMiddleware, self).before()


	def after(self, response):

		if 'handledAfter' not in g:
			g.handledAfter = 0
		g.handledAfter += 1

		if 'firstHandledAfter' not in g:
			g.firstHandledAfter = MyRequestMiddleware

		g.lastHandledAfter = MyRequestMiddleware

		return super(MyRequestMiddleware, self).after(response)


class MySecondRequestMiddleware(RequestMiddleware):
	"""
	Second Request Middleware class
	"""

	def before(self):

		if 'handledBefore' not in g:
			g.handledBefore = 0
		g.handledBefore += 1

		if 'firstHandledBefore' not in g:
			g.firstHandledBefore = MySecondRequestMiddleware

		g.lastHandledBefore = MySecondRequestMiddleware

		return super(MySecondRequestMiddleware, self).before()


	def after(self, response):

		if 'handledAfter' not in g:
			g.handledAfter = 0
		g.handledAfter += 1

		if 'firstHandledAfter' not in g:
			g.firstHandledAfter = MySecondRequestMiddleware

		g.lastHandledAfter = MySecondRequestMiddleware

		return super(MySecondRequestMiddleware, self).after(response)