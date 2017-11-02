
from tests.testcase import TestCase
from edmunds.http.controller import Controller
from edmunds.http.requestmiddleware import RequestMiddleware
from flask import Response


class TestRoute(TestCase):
    """
    Test the Request Routing
    """

    cache = None

    def set_up(self):
        """
        Set up the test case
        """

        super(TestRoute, self).set_up()

        TestRoute.cache = dict()
        TestRoute.cache['timeline'] = []

    def test_original_routing(self):
        """
        Test original routing
        """

        rule = '/' + self.rand_str(20)

        # Add route
        @self.app.route(rule)
        def handle_route():
            TestRoute.cache['timeline'].append('handle_route')
            return ''

        # Call route
        with self.app.test_client() as c:
            c.get(rule)

            self.assert_equal(1, len(TestRoute.cache['timeline']))

            self.assert_in('handle_route', TestRoute.cache['timeline'])
            self.assert_equal(0, TestRoute.cache['timeline'].index('handle_route'))

    def test_original_routing_with_parameter(self):
        """
        Test original routing with parameter
        """

        rule = '/' + self.rand_str(20)
        rule_with_param = rule + '/<param>'
        param = 'myparam'

        # Add route
        @self.app.route(rule_with_param)
        def handle_route(param=None):
            TestRoute.cache['timeline'].append('handle_route')
            TestRoute.cache['param'] = param
            return ''

        # Call route
        with self.app.test_client() as c:
            c.get(rule + '/' + param)

            self.assert_equal(1, len(TestRoute.cache['timeline']))

            self.assert_in('handle_route', TestRoute.cache['timeline'])
            self.assert_equal(0, TestRoute.cache['timeline'].index('handle_route'))

            self.assert_in('param', TestRoute.cache)
            self.assert_equal(param, TestRoute.cache['param'])

    def test_new_routing(self):
        """
        Test new routing
        """

        rule = '/' + self.rand_str(20)

        # Add route
        self.app.route(rule, uses=(MyController, 'get'))

        # Call route
        with self.app.test_client() as c:
            c.get(rule)

            self.assert_equal(1, len(TestRoute.cache['timeline']))

            self.assert_in('handle_route', TestRoute.cache['timeline'])
            self.assert_equal(0, TestRoute.cache['timeline'].index('handle_route'))

    def test_new_routing_with_parameter(self):
        """
        Test new routing with parameter
        """

        rule = '/' + self.rand_str(20)
        rule_with_param = rule + '/<param>'
        param = 'myparam'

        # Add route
        self.app.route(rule_with_param, uses=(MyController, 'get_with_param'))

        # Call route
        with self.app.test_client() as c:
            c.get(rule + '/' + param)

            self.assert_equal(1, len(TestRoute.cache['timeline']))

            self.assert_in('handle_route', TestRoute.cache['timeline'])
            self.assert_equal(0, TestRoute.cache['timeline'].index('handle_route'))

            self.assert_in('param', TestRoute.cache)
            self.assert_equal(param, TestRoute.cache['param'])

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

            self.assert_in('init_params', TestRoute.cache)
            self.assert_equal(0, len(TestRoute.cache['init_params']))

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

            self.assert_in('init_params', TestRoute.cache)
            self.assert_equal(1, len(TestRoute.cache['init_params']))
            self.assert_in('param', TestRoute.cache['init_params'])
            self.assert_equal(param, TestRoute.cache['init_params']['param'])

    def test_faulty_routing(self):
        """
        Test faulty routing
        """

        rule = '/' + self.rand_str(20)

        # Add route with both uses and handler
        with self.assert_raises_regexp(TypeError, "'Route' object is not callable"):

            @self.app.route(rule, uses=(MyController, 'get'))
            def handle_route():
                pass

    def test_middleware_no_middleware(self):
        """
        Test route with no middleware
        """

        rule = '/' + self.rand_str(20)

        # Add route
        @self.app.route(rule)
        def handle_route():
            TestRoute.cache['timeline'].append('handle_route')
            return ''

        # Call route
        with self.app.test_client() as c:
            c.get(rule)

            self.assert_equal(1, len(TestRoute.cache['timeline']))

            self.assert_in('handle_route', TestRoute.cache['timeline'])
            self.assert_equal(0, TestRoute.cache['timeline'].index('handle_route'))

    def test_middleware_registering(self):
        """
        Test registering the request middleware
        """

        rule = '/' + self.rand_str(20)
        rule2 = '/' + self.rand_str(20)
        self.assert_not_equal(rule, rule2)

        # Add route
        @self.app.route(rule, middleware=[MyRequestMiddleware])
        def handle_route():
            TestRoute.cache['timeline'].append('handle_route')
            return ''

        # Call route
        with self.app.test_request_context(rule):
            self.app.preprocess_request()
            rv = self.app.dispatch_request()
            response = self.app.make_response(rv)
            response = self.app.process_response(response)

            self.assert_equal(3, len(TestRoute.cache['timeline']))

            self.assert_in(MyRequestMiddleware.__name__ + '.before', TestRoute.cache['timeline'])
            self.assert_equal(0, TestRoute.cache['timeline'].index(MyRequestMiddleware.__name__ + '.before'))

            self.assert_in('handle_route', TestRoute.cache['timeline'])
            self.assert_equal(1, TestRoute.cache['timeline'].index('handle_route'))

            self.assert_in(MyRequestMiddleware.__name__ + '.after', TestRoute.cache['timeline'])
            self.assert_equal(2, TestRoute.cache['timeline'].index(MyRequestMiddleware.__name__ + '.after'))

        # Add second route
        @self.app.route(rule2, middleware=[MyRequestMiddleware, (MySecondRequestMiddleware, 'arg1')])
        def handleecond_route():
            TestRoute.cache['timeline'].append('handle_route')
            return ''

        # Call route
        TestRoute.cache = dict()
        TestRoute.cache['timeline'] = []
        with self.app.test_request_context(rule2):
            self.app.preprocess_request()
            rv = self.app.dispatch_request()
            response = self.app.make_response(rv)
            response = self.app.process_response(response)

            self.assert_equal(5, len(TestRoute.cache['timeline']))

            self.assert_in(MyRequestMiddleware.__name__ + '.before', TestRoute.cache['timeline'])
            self.assert_equal(0, TestRoute.cache['timeline'].index(MyRequestMiddleware.__name__ + '.before'))

            self.assert_in(MySecondRequestMiddleware.__name__ + '.before', TestRoute.cache['timeline'])
            self.assert_equal(1, TestRoute.cache['timeline'].index(MySecondRequestMiddleware.__name__ + '.before'))

            self.assert_in('handle_route', TestRoute.cache['timeline'])
            self.assert_equal(2, TestRoute.cache['timeline'].index('handle_route'))

            self.assert_in(MySecondRequestMiddleware.__name__ + '.after', TestRoute.cache['timeline'])
            self.assert_equal(3, TestRoute.cache['timeline'].index(MySecondRequestMiddleware.__name__ + '.after'))

            self.assert_in(MyRequestMiddleware.__name__ + '.after', TestRoute.cache['timeline'])
            self.assert_equal(4, TestRoute.cache['timeline'].index(MyRequestMiddleware.__name__ + '.after'))

    def test_middleware_overwriting(self):
        """
        Test overwriting of middleware
        """

        rule = '/' + self.rand_str(20)
        rule2 = '/' + self.rand_str(20)

        # Add route
        @self.app.route(rule, middleware=[MyRequestMiddleware])
        def handle_route():
            pass

        # Overwrite route
        @self.app.route(rule2, middleware=[MyRequestMiddleware, (MySecondRequestMiddleware, 'arg1')])
        def handleOverwrittenRoute():
            pass

    def test_middleware_before_returning_none_null(self):
        """
        Test before returning none null
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Overwrite route
        @self.app.route(rule, middleware=[(MyThirdRequestMiddleware, 'arg1'), MyRequestMiddleware])
        def handle_route():
            TestRoute.cache['timeline'].append('handle_route')
            return ''

        # Call route
        with self.app.test_request_context(rule):
            self.app.preprocess_request()
            rv = self.app.dispatch_request()
            response = self.app.make_response(rv)
            self.app.process_response(response)

            self.assert_equal(1, len(TestRoute.cache['timeline']))

            self.assert_equal(MyThirdRequestMiddleware.__name__ + '.before', TestRoute.cache['timeline'][0])

    def test_middleware_with_uses(self):
        """
        Test middleware with uses
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Overwrite route
        self.app.route(rule, middleware=[(MySecondRequestMiddleware, 'arg1'), MyRequestMiddleware], uses=(MyController, 'get'))

        # Call route
        with self.app.test_request_context(rule):
            self.app.preprocess_request()
            rv = self.app.dispatch_request()
            response = self.app.make_response(rv)
            self.app.process_response(response)

            self.assert_equal(5, len(TestRoute.cache['timeline']))

            self.assert_equal(MySecondRequestMiddleware.__name__ + '.before', TestRoute.cache['timeline'][0])
            self.assert_equal(MyRequestMiddleware.__name__ + '.before', TestRoute.cache['timeline'][1])
            self.assert_equal(2, TestRoute.cache['timeline'].index('handle_route'))
            self.assert_equal(MyRequestMiddleware.__name__ + '.after', TestRoute.cache['timeline'][3])
            self.assert_equal(MySecondRequestMiddleware.__name__ + '.after', TestRoute.cache['timeline'][4])

    def test_middleware_with_uses_with_function(self):
        """
        Test middleware with uses with function
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Overwrite route
        self.app.route(rule, uses=(MyController, 'get')) \
            .middleware(MySecondRequestMiddleware, 'arg1', kwarg1='some value') \
            .middleware(MyRequestMiddleware)

        # Call route
        with self.app.test_request_context(rule):
            self.app.preprocess_request()
            rv = self.app.dispatch_request()
            response = self.app.make_response(rv)
            self.app.process_response(response)

            self.assert_equal(5, len(TestRoute.cache['timeline']))

            self.assert_equal(MySecondRequestMiddleware.__name__ + '.before', TestRoute.cache['timeline'][0])
            self.assert_equal(MyRequestMiddleware.__name__ + '.before', TestRoute.cache['timeline'][1])
            self.assert_equal(2, TestRoute.cache['timeline'].index('handle_route'))
            self.assert_equal(MyRequestMiddleware.__name__ + '.after', TestRoute.cache['timeline'][3])
            self.assert_equal(MySecondRequestMiddleware.__name__ + '.after', TestRoute.cache['timeline'][4])


class MyController(Controller):

    def initialize(self, **params):
        TestRoute.cache['init_params'] = params
        super(MyController, self).initialize(**params)

    def get(self):
        TestRoute.cache['timeline'].append('handle_route')
        return ''

    def get_with_param(self, param=None):
        TestRoute.cache['timeline'].append('handle_route')
        TestRoute.cache['param'] = param
        return ''


class MyRequestMiddleware(RequestMiddleware):
    """
    Request Middleware class
    """

    def before(self):

        TestRoute.cache['timeline'].append(self.__class__.__name__ + '.before')

        return super(MyRequestMiddleware, self).before()

    def after(self, response):
        assert isinstance(response, Response)

        TestRoute.cache['timeline'].append(self.__class__.__name__ + '.after')

        return super(MyRequestMiddleware, self).after(response)


class MySecondRequestMiddleware(RequestMiddleware):
    """
    Second Request Middleware class
    """

    def before(self, arg1, kwarg1=None):

        TestRoute.cache['timeline'].append(self.__class__.__name__ + '.before')

        return super(MySecondRequestMiddleware, self).before()

    def after(self, response, arg1, kwarg1=None):
        assert isinstance(response, Response)

        TestRoute.cache['timeline'].append(self.__class__.__name__ + '.after')

        return super(MySecondRequestMiddleware, self).after(response)


class MyThirdRequestMiddleware(RequestMiddleware):
    """
    Third Request Middleware class
    """

    def before(self, arg1, kwarg1=None):

        TestRoute.cache['timeline'].append(self.__class__.__name__ + '.before')

        return 'Not none'

    def after(self, response, arg1, kwarg1=None):
        assert isinstance(response, Response)

        TestRoute.cache['timeline'].append(self.__class__.__name__ + '.after')

        return super(MyThirdRequestMiddleware, self).after(response)
