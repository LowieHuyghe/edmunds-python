
from tests.testcase import TestCase
from edmunds.http.controller import Controller


class TestRequestRouting(TestCase):
    """
    Test the Request Routing
    """

    cache = None

    def set_up(self):
        """
        Set up the test case
        """

        super(TestRequestRouting, self).set_up()

        TestRequestRouting.cache = dict()
        TestRequestRouting.cache['timeline'] = []

    def test_original_routing(self):
        """
        Test original routing
        """

        rule = '/' + self.rand_str(20)

        # Check empty
        self.assert_not_in(rule, self.app._pre_request_uses_by_rule)
        self.assert_not_in(rule, self.app._request_uses_by_rule)

        # Add route
        @self.app.route(rule)
        def handle_route():
            TestRequestRouting.cache['timeline'].append('handle_route')
            return ''

        # Check uses empty
        self.assert_not_in(rule, self.app._pre_request_uses_by_rule)
        self.assert_not_in(rule, self.app._request_uses_by_rule)

        # Call route
        with self.app.test_client() as c:
            c.get(rule)

            self.assert_equal(1, len(TestRequestRouting.cache['timeline']))

            self.assert_in('handle_route', TestRequestRouting.cache['timeline'])
            self.assert_equal(0, TestRequestRouting.cache['timeline'].index('handle_route'))

    def test_original_routing_with_parameter(self):
        """
        Test original routing with parameter
        """

        rule = '/' + self.rand_str(20)
        rule_with_param = rule + '/<param>'
        param = 'myparam'

        # Check empty
        self.assert_not_in(rule_with_param, self.app._pre_request_uses_by_rule)
        self.assert_not_in(rule_with_param, self.app._request_uses_by_rule)

        # Add route
        @self.app.route(rule_with_param)
        def handle_route(param=None):
            TestRequestRouting.cache['timeline'].append('handle_route')
            TestRequestRouting.cache['param'] = param
            return ''

        # Check uses empty
        self.assert_not_in(rule_with_param, self.app._pre_request_uses_by_rule)
        self.assert_not_in(rule_with_param, self.app._request_uses_by_rule)

        # Call route
        with self.app.test_client() as c:
            c.get(rule + '/' + param)

            self.assert_equal(1, len(TestRequestRouting.cache['timeline']))

            self.assert_in('handle_route', TestRequestRouting.cache['timeline'])
            self.assert_equal(0, TestRequestRouting.cache['timeline'].index('handle_route'))

            self.assert_in('param', TestRequestRouting.cache)
            self.assert_equal(param, TestRequestRouting.cache['param'])

    def test_new_routing(self):
        """
        Test new routing
        """

        rule = '/' + self.rand_str(20)

        # Check empty
        self.assert_not_in(rule, self.app._pre_request_uses_by_rule)
        self.assert_not_in(rule, self.app._request_uses_by_rule)

        # Add route
        self.app.route(rule, uses=(MyController, 'get'))

        # Check uses empty
        self.assert_not_in(rule, self.app._pre_request_uses_by_rule)
        self.assert_in(rule, self.app._request_uses_by_rule)

        # Call route
        with self.app.test_client() as c:
            c.get(rule)

            self.assert_equal(1, len(TestRequestRouting.cache['timeline']))

            self.assert_in('handle_route', TestRequestRouting.cache['timeline'])
            self.assert_equal(0, TestRequestRouting.cache['timeline'].index('handle_route'))

    def test_new_routing_with_parameter(self):
        """
        Test new routing with parameter
        """

        rule = '/' + self.rand_str(20)
        rule_with_param = rule + '/<param>'
        param = 'myparam'

        # Check empty
        self.assert_not_in(rule_with_param, self.app._pre_request_uses_by_rule)
        self.assert_not_in(rule_with_param, self.app._request_uses_by_rule)

        # Add route
        self.app.route(rule_with_param, uses = (MyController, 'get_with_param'))

        # Check uses empty
        self.assert_not_in(rule_with_param, self.app._pre_request_uses_by_rule)
        self.assert_in(rule_with_param, self.app._request_uses_by_rule)

        # Call route
        with self.app.test_client() as c:
            c.get(rule + '/' + param)

            self.assert_equal(1, len(TestRequestRouting.cache['timeline']))

            self.assert_in('handle_route', TestRequestRouting.cache['timeline'])
            self.assert_equal(0, TestRequestRouting.cache['timeline'].index('handle_route'))

            self.assert_in('param', TestRequestRouting.cache)
            self.assert_equal(param, TestRequestRouting.cache['param'])

    def test_initialize(self):
        """
        Test initialize
        """

        rule = '/' + self.rand_str(20)

        # Add route
        self.app.route(rule, uses=(MyController, 'get'))

        # Call route
        with self.app.test_client() as c:
            c.get(rule)

            self.assert_in('init_params', TestRequestRouting.cache)
            self.assert_equal(0, len(TestRequestRouting.cache['init_params']))

    def test_initialize_with_parameter(self):
        """
        Test initialize with parameter
        """

        rule = '/' + self.rand_str(20)
        rule_with_param = rule + '/<param>'
        param = 'myparam'

        # Add route
        self.app.route(rule_with_param, uses=(MyController, 'get_with_param'))

        # Call route
        with self.app.test_client() as c:
            c.get(rule + '/' + param)

            self.assert_in('init_params', TestRequestRouting.cache)
            self.assert_equal(1, len(TestRequestRouting.cache['init_params']))
            self.assert_in('param', TestRequestRouting.cache['init_params'])
            self.assert_equal(param, TestRequestRouting.cache['init_params']['param'])

    def test_faulty_routing(self):
        """
        Test faulty routing
        """

        rule = '/' + self.rand_str(20)

        # Add route with both uses and handler
        with self.assert_raises_regexp(TypeError, "'NoneType' object is not callable"):

            @self.app.route(rule, uses=(MyController, 'get'))
            def handle_route():
                pass


class MyController(Controller):

    def initialize(self, **params):
        TestRequestRouting.cache['init_params'] = params
        super(MyController, self).initialize(**params)

    def get(self):
        TestRequestRouting.cache['timeline'].append('handle_route')
        return ''

    def get_with_param(self, param=None):
        TestRequestRouting.cache['timeline'].append('handle_route')
        TestRequestRouting.cache['param'] = param
        return ''
