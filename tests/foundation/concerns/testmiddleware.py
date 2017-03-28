
from tests.testcase import TestCase
from edmunds.foundation.applicationmiddleware import ApplicationMiddleware
from edmunds.http.requestmiddleware import RequestMiddleware


class TestMiddleware(TestCase):
    """
    Test the Middleware
    """

    cache = None

    def set_up(self):
        """
        Set up the test case
        """

        super(TestMiddleware, self).set_up()

        TestMiddleware.cache = dict()
        TestMiddleware.cache['timeline'] = []

    def test_registering_application_middleware(self):
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

    def test_handling_application_middleware(self):
        """
        Test handling of application middleware
        """

        # Register the middleware
        self.app.middleware(MyApplicationMiddleware)
        # Add it a second time to make sure it is only called once
        self.app.middleware(MyApplicationMiddleware)

        # Add route
        rule = '/' + self.rand_str(20)
        @self.app.route(rule)
        def handle_route():
            TestMiddleware.cache['timeline'].append('handle_route')
            return ''

        # Call route
        with self.app.test_client() as c:
            rv = c.get(rule)

            self.assert_equal(2, len(TestMiddleware.cache['timeline']))

            self.assert_in(MyApplicationMiddleware.__name__, TestMiddleware.cache['timeline'])
            self.assert_equal(0, TestMiddleware.cache['timeline'].index(MyApplicationMiddleware.__name__))

            self.assert_in('handle_route', TestMiddleware.cache['timeline'])
            self.assert_equal(1, TestMiddleware.cache['timeline'].index('handle_route'))

        # Add second middleware
        self.app.middleware(MySecondApplicationMiddleware)

        # Call route
        TestMiddleware.cache = dict()
        TestMiddleware.cache['timeline'] = []
        with self.app.test_client() as c:
            rv = c.get(rule)

            self.assert_equal(3, len(TestMiddleware.cache['timeline']))

            self.assert_in(MySecondApplicationMiddleware.__name__, TestMiddleware.cache['timeline'])
            self.assert_equal(0, TestMiddleware.cache['timeline'].index(MySecondApplicationMiddleware.__name__))

            self.assert_in(MyApplicationMiddleware.__name__, TestMiddleware.cache['timeline'])
            self.assert_equal(1, TestMiddleware.cache['timeline'].index(MyApplicationMiddleware.__name__))

            self.assert_in('handle_route', TestMiddleware.cache['timeline'])
            self.assert_equal(2, TestMiddleware.cache['timeline'].index('handle_route'))

    def test_no_middleware_request_middleware(self):
        """
        Test route with no middleware
        """

        rule = '/' + self.rand_str(20)

        # Check empty
        self.assert_not_in(rule, self.app._request_middleware_by_rule)

        # Add route
        @self.app.route(rule)
        def handle_route():
            TestMiddleware.cache['timeline'].append('handle_route')
            return ''

        # Check middleware empty
        self.assert_not_in(rule, self.app._request_middleware_by_rule)

        # Call route
        with self.app.test_client() as c:
            c.get(rule)

            self.assert_equal(1, len(TestMiddleware.cache['timeline']))

            self.assert_in('handle_route', TestMiddleware.cache['timeline'])
            self.assert_equal(0, TestMiddleware.cache['timeline'].index('handle_route'))

    def test_registering_request_middleware(self):
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
            TestMiddleware.cache['timeline'].append('handle_route')
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

            self.assert_equal(3, len(TestMiddleware.cache['timeline']))

            self.assert_in(MyRequestMiddleware.__name__ + '.before', TestMiddleware.cache['timeline'])
            self.assert_equal(0, TestMiddleware.cache['timeline'].index(MyRequestMiddleware.__name__ + '.before'))

            self.assert_in('handle_route', TestMiddleware.cache['timeline'])
            self.assert_equal(1, TestMiddleware.cache['timeline'].index('handle_route'))

            self.assert_in(MyRequestMiddleware.__name__ + '.after', TestMiddleware.cache['timeline'])
            self.assert_equal(2, TestMiddleware.cache['timeline'].index(MyRequestMiddleware.__name__ + '.after'))

        # Add second route
        @self.app.route(rule2, middleware=[MyRequestMiddleware, MySecondRequestMiddleware])
        def handleecond_route():
            TestMiddleware.cache['timeline'].append('handle_route')
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
        TestMiddleware.cache = dict()
        TestMiddleware.cache['timeline'] = []
        with self.app.test_request_context(rule2):
            self.app.preprocess_request()
            rv = self.app.dispatch_request()
            response = self.app.make_response(rv)
            response = self.app.process_response(response)

            self.assert_equal(5, len(TestMiddleware.cache['timeline']))

            self.assert_in(MyRequestMiddleware.__name__ + '.before', TestMiddleware.cache['timeline'])
            self.assert_equal(0, TestMiddleware.cache['timeline'].index(MyRequestMiddleware.__name__ + '.before'))

            self.assert_in(MySecondRequestMiddleware.__name__ + '.before', TestMiddleware.cache['timeline'])
            self.assert_equal(1, TestMiddleware.cache['timeline'].index(MySecondRequestMiddleware.__name__ + '.before'))

            self.assert_in('handle_route', TestMiddleware.cache['timeline'])
            self.assert_equal(2, TestMiddleware.cache['timeline'].index('handle_route'))

            self.assert_in(MySecondRequestMiddleware.__name__ + '.after', TestMiddleware.cache['timeline'])
            self.assert_equal(3, TestMiddleware.cache['timeline'].index(MySecondRequestMiddleware.__name__ + '.after'))

            self.assert_in(MyRequestMiddleware.__name__ + '.after', TestMiddleware.cache['timeline'])
            self.assert_equal(4, TestMiddleware.cache['timeline'].index(MyRequestMiddleware.__name__ + '.after'))

    def test_overwriting_request_middleware(self):
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

    def test_before_returning_none_null_request_middleware(self):
        """
        Test before returning none null
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Overwrite route
        @self.app.route(rule, middleware=[MyThirdRequestMiddleware, MyRequestMiddleware])
        def handle_route():
            TestMiddleware.cache['timeline'].append('handle_route')
            return ''

        # Call route
        with self.app.test_request_context(rule):
            self.app.preprocess_request()
            rv = self.app.dispatch_request()
            response = self.app.make_response(rv)
            self.app.process_response(response)

            self.assert_equal(4, len(TestMiddleware.cache['timeline']))

            self.assert_equal(MyThirdRequestMiddleware.__name__ + '.before', TestMiddleware.cache['timeline'][0])
            self.assert_equal('handle_route', TestMiddleware.cache['timeline'][1])
            self.assert_equal(MyRequestMiddleware.__name__ + '.after', TestMiddleware.cache['timeline'][2])
            self.assert_equal(MyThirdRequestMiddleware.__name__ + '.after', TestMiddleware.cache['timeline'][3])


class MyApplicationMiddleware(ApplicationMiddleware):
    """
    Application Middleware class
    """

    def handle(self, environment, start_response):

        TestMiddleware.cache['timeline'].append(self.__class__.__name__)

        return super(MyApplicationMiddleware, self).handle(environment, start_response)


class MySecondApplicationMiddleware(ApplicationMiddleware):
    """
    Second Application Middleware class
    """

    def handle(self, environment, start_response):

        TestMiddleware.cache['timeline'].append(self.__class__.__name__)

        return super(MySecondApplicationMiddleware, self).handle(environment, start_response)

class MyRequestMiddleware(RequestMiddleware):
    """
    Request Middleware class
    """

    def before(self):

        TestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.before')

        return super(MyRequestMiddleware, self).before()

    def after(self, response):

        TestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.after')

        return super(MyRequestMiddleware, self).after(response)


class MySecondRequestMiddleware(RequestMiddleware):
    """
    Second Request Middleware class
    """

    def before(self):

        TestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.before')

        return super(MySecondRequestMiddleware, self).before()

    def after(self, response):

        TestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.after')

        return super(MySecondRequestMiddleware, self).after(response)


class MyThirdRequestMiddleware(RequestMiddleware):
    """
    Third Request Middleware class
    """

    def before(self):

        TestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.before')

        return 'Not none'

    def after(self, response):

        TestMiddleware.cache['timeline'].append(self.__class__.__name__ + '.after')

        return super(MyThirdRequestMiddleware, self).after(response)
