
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers
from Edmunds.Http.Controller import Controller


class RequestRoutingTest(TestCase):
	"""
	Test the Request Routing
	"""

	cache = None


	def set_up(self):
		"""
		Set up the test case
		"""

		super(RequestRoutingTest, self).set_up()

		RequestRoutingTest.cache = {}
		RequestRoutingTest.cache['timeline'] = []


	def test_original_routing(self):
		"""
		Test original routing
		"""

		rule = '/' + helpers.random_str(20)

		# Check empty
		self.assert_not_in(rule, self.app._pre_request_uses_by_rule)
		self.assert_not_in(rule, self.app._request_uses_by_rule)

		# Add route
		@self.app.route(rule)
		def handleRoute():
			RequestRoutingTest.cache['timeline'].append('handleRoute')
			return ''

		# Check middleware empty
		self.assert_not_in(rule, self.app._pre_request_uses_by_rule)
		self.assert_not_in(rule, self.app._request_uses_by_rule)

		# Call route
		with self.app.test_client() as c:
			c.get(rule)

			self.assert_in('handleRoute', RequestRoutingTest.cache['timeline'])
			self.assert_equal(0, RequestRoutingTest.cache['timeline'].index('handleRoute'))


	def test_original_routing_with_parameter(self):
		"""
		Test original routing with parameter
		"""

		rule = '/' + helpers.random_str(20)
		rule_with_param = rule + '/<param>'
		param = 'myparam'

		# Check empty
		self.assert_not_in(rule_with_param, self.app._pre_request_uses_by_rule)
		self.assert_not_in(rule_with_param, self.app._request_uses_by_rule)

		# Add route
		@self.app.route(rule_with_param)
		def handleRoute(param = None):
			RequestRoutingTest.cache['timeline'].append('handleRoute')
			RequestRoutingTest.cache['param'] = param
			return ''

		# Check middleware empty
		self.assert_not_in(rule_with_param, self.app._pre_request_uses_by_rule)
		self.assert_not_in(rule_with_param, self.app._request_uses_by_rule)

		# Call route
		with self.app.test_client() as c:
			c.get(rule + '/' + param)

			self.assert_in('handleRoute', RequestRoutingTest.cache['timeline'])
			self.assert_equal(0, RequestRoutingTest.cache['timeline'].index('handleRoute'))

			self.assert_in('param', RequestRoutingTest.cache)
			self.assert_equal(param, RequestRoutingTest.cache['param'])


	def test_new_routing(self):
		"""
		Test new routing
		"""

		rule = '/' + helpers.random_str(20)

		# Check empty
		self.assert_not_in(rule, self.app._pre_request_uses_by_rule)
		self.assert_not_in(rule, self.app._request_uses_by_rule)

		# Add route
		self.app.route(rule, uses = (MyController, 'get'))

		# Check middleware empty
		self.assert_not_in(rule, self.app._pre_request_uses_by_rule)
		self.assert_in(rule, self.app._request_uses_by_rule)

		# Call route
		with self.app.test_client() as c:
			c.get(rule)

			self.assert_in('handleRoute', RequestRoutingTest.cache['timeline'])
			self.assert_equal(0, RequestRoutingTest.cache['timeline'].index('handleRoute'))


	def test_new_routing_with_parameter(self):
		"""
		Test new routing with parameter
		"""

		rule = '/' + helpers.random_str(20)
		rule_with_param = rule + '/<param>'
		param = 'myparam'

		# Check empty
		self.assert_not_in(rule_with_param, self.app._pre_request_uses_by_rule)
		self.assert_not_in(rule_with_param, self.app._request_uses_by_rule)

		# Add route
		self.app.route(rule_with_param, uses = (MyController, 'get_with_param'))

		# Check middleware empty
		self.assert_not_in(rule_with_param, self.app._pre_request_uses_by_rule)
		self.assert_in(rule_with_param, self.app._request_uses_by_rule)

		# Call route
		with self.app.test_client() as c:
			c.get(rule + '/' + param)

			self.assert_in('handleRoute', RequestRoutingTest.cache['timeline'])
			self.assert_equal(0, RequestRoutingTest.cache['timeline'].index('handleRoute'))

			self.assert_in('param', RequestRoutingTest.cache)
			self.assert_equal(param, RequestRoutingTest.cache['param'])



class MyController(Controller):

	def get(self):
		RequestRoutingTest.cache['timeline'].append('handleRoute')
		return ''

	def get_with_param(self, param = None):
		RequestRoutingTest.cache['timeline'].append('handleRoute')
		RequestRoutingTest.cache['param'] = param
		return ''
