
from tests.testcase import TestCase
from edmunds.http.requestmiddleware import RequestMiddleware


class TestRequestMiddleware(TestCase):
    """
    Test the Request Middleware
    """

    cache = None

    def set_up(self):
        """
        Set up the test case
        """

        super(TestRequestMiddleware, self).set_up()

        TestRequestMiddleware.cache = dict()
        TestRequestMiddleware.cache['timeline'] = []

    def test_no_middleware(self):
        """
        Test route with no middleware
        """

        rule = '/' + self.rand_str(20)

        # Check empty
        self.assert_not_in(rule, self.app._request_middleware_by_rule)

        # Add route
        @self.app.route(rule)
        def handle_route():
            TestRequestMiddleware.cache['timeline'].append('handle_route')
            return ''

        # Check middleware empty
        self.assert_not_in(rule, self.app._request_middleware_by_rule)

        # Call route
        with self.app.test_client() as c:
            c.get(rule)

            self.assert_equal(1, len(TestRequestMiddleware.cache['timeline']))

            self.assert_in('handle_route', TestRequestMiddleware.cache['timeline'])
            self.assert_equal(0, TestRequestMiddleware.cache['timeline'].index('handle_route'))

    def test_registering(self):
        """
        Test registering the request middleware
        """

        rule = '/' + self.rand_str(20)
        rule2 = '/' + self.rand_str(20)
        self.assert_not_equal(rule, rule2)

        # Check empty
        self.assert_not_in(rule, self.app._request_middleware_by_rule)
        self.assert_not_in(rule2, self.app._request_middleware_by_rule)

        # Add route
        @self.app.route(rule, middleware=[MyRequestMiddleware])
        def handle_route():
            TestRequestMiddleware.cache['timeline'].append('handle_route')
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

            self.assert_equal(3, len(TestRequestMiddleware.cache['timeline']))

            self.assert_in(MyRequestMiddleware.__name__ + '.before', TestRequestMiddleware.cache['timeline'])
            self.assert_equal(0, TestRequestMiddleware.cache['timeline'].index(MyRequestMiddleware.__name__ + '.before'))

            self.assert_in('handle_route', TestRequestMiddleware.cache['timeline'])
            self.assert_equal(1, TestRequestMiddleware.cache['timeline'].index('handle_route'))

            self.assert_in(MyRequestMiddleware.__name__ + '.after', TestRequestMiddleware.cache['timeline'])
            self.assert_equal(2, TestRequestMiddleware.cache['timeline'].index(MyRequestMiddleware.__name__ + '.after'))

        # Add second route
        @self.app.route(rule2, middleware=[MyRequestMiddleware, MySecondRequestMiddleware])
        def handleecond_route():
            TestRequestMiddleware.cache['timeline'].append('handle_route')
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
        TestRequestMiddleware.cache = dict()
        TestRequestMiddleware.cache['timeline'] = []
        with self.app.test_request_context(rule2):
            self.app.preprocess_request()
            rv = self.app.dispatch_request()
            response = self.app.make_response(rv)
            response = self.app.process_response(response)

            self.assert_equal(5, len(TestRequestMiddleware.cache['timeline']))

            self.assert_in(MyRequestMiddleware.__name__ + '.before', TestRequestMiddleware.cache['timeline'])
            self.assert_equal(0, TestRequestMiddleware.cache['timeline'].index(MyRequestMiddleware.__name__ + '.before'))

            self.assert_in(MySecondRequestMiddleware.__name__ + '.before', TestRequestMiddleware.cache['timeline'])
            self.assert_equal(1, TestRequestMiddleware.cache['timeline'].index(MySecondRequestMiddleware.__name__ + '.before'))

            self.assert_in('handle_route', TestRequestMiddleware.cache['timeline'])
            self.assert_equal(2, TestRequestMiddleware.cache['timeline'].index('handle_route'))

            self.assert_in(MySecondRequestMiddleware.__name__ + '.after', TestRequestMiddleware.cache['timeline'])
            self.assert_equal(3, TestRequestMiddleware.cache['timeline'].index(MySecondRequestMiddleware.__name__ + '.after'))

            self.assert_in(MyRequestMiddleware.__name__ + '.after', TestRequestMiddleware.cache['timeline'])
            self.assert_equal(4, TestRequestMiddleware.cache['timeline'].index(MyRequestMiddleware.__name__ + '.after'))

    def test_overwriting(self):
        """
        Test overwriting of middleware
        """

        rule = '/' + self.rand_str(20)

        # Check empty
        self.assert_not_in(rule, self.app._request_middleware_by_rule)

        # Add route
        @self.app.route(rule, middleware=[MyRequestMiddleware])
        def handle_route():
            pass

        # Check middleware
        self.assert_in(rule, self.app._request_middleware_by_rule)
        self.assert_equal(1, len(self.app._request_middleware_by_rule[rule]))

        # Overwrite route
        @self.app.route(rule, middleware=[MyRequestMiddleware, MySecondRequestMiddleware])
        def handleOverwrittenRoute():
            pass

        # Check middleware
        self.assert_in(rule, self.app._request_middleware_by_rule)
        self.assert_equal(2, len(self.app._request_middleware_by_rule[rule]))

    def test_before_returning_none_null(self):
        """
        Test before returning none null
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Overwrite route
        @self.app.route(rule, middleware=[MyThirdRequestMiddleware, MyRequestMiddleware])
        def handle_route():
            TestRequestMiddleware.cache['timeline'].append('handle_route')
            return ''

        # Call route
        with self.app.test_request_context(rule):
            self.app.preprocess_request()
            rv = self.app.dispatch_request()
            response = self.app.make_response(rv)
            self.app.process_response(response)

            self.assert_equal(4, len(TestRequestMiddleware.cache['timeline']))

            self.assert_equal(MyThirdRequestMiddleware.__name__ + '.before', TestRequestMiddleware.cache['timeline'][0])
            self.assert_equal('handle_route', TestRequestMiddleware.cache['timeline'][1])
            self.assert_equal(MyRequestMiddleware.__name__ + '.after', TestRequestMiddleware.cache['timeline'][2])
            self.assert_equal(MyThirdRequestMiddleware.__name__ + '.after', TestRequestMiddleware.cache['timeline'][3])


class MyRequestMiddleware(RequestMiddleware):
    """
    Request Middleware class
    """

    def before(self):

        TestRequestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.before')

        return super(MyRequestMiddleware, self).before()

    def after(self, response):

        TestRequestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.after')

        return super(MyRequestMiddleware, self).after(response)


class MySecondRequestMiddleware(RequestMiddleware):
    """
    Second Request Middleware class
    """

    def before(self):

        TestRequestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.before')

        return super(MySecondRequestMiddleware, self).before()

    def after(self, response):

        TestRequestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.after')

        return super(MySecondRequestMiddleware, self).after(response)


class MyThirdRequestMiddleware(RequestMiddleware):
    """
    Third Request Middleware class
    """

    def before(self):

        TestRequestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.before')

        return 'Not none'

    def after(self, response):

        TestRequestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.after')

        return super(MyThirdRequestMiddleware, self).after(response)
