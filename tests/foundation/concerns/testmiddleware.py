
from tests.testcase import TestCase
from edmunds.foundation.applicationmiddleware import ApplicationMiddleware


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
        self.assert_not_in('edmunds.applicationmiddleware.middleware', self.app.extensions)

        # Register the middleware
        self.app.middleware(MyApplicationMiddleware)

        # Check if registered
        self.assert_equal(1, self.app.extensions['edmunds.applicationmiddleware.middleware'].count(MyApplicationMiddleware))
        self.assert_is_instance(self.app.wsgi_app, MyApplicationMiddleware)
        self.assert_not_is_instance(self.app.wsgi_app.wsgi_app, MyApplicationMiddleware)

        # Try adding it again
        self.app.middleware(MyApplicationMiddleware)

        # Check if duplicate
        self.assert_equal(1, self.app.extensions['edmunds.applicationmiddleware.middleware'].count(MyApplicationMiddleware))
        self.assert_is_instance(self.app.wsgi_app, MyApplicationMiddleware)
        self.assert_not_is_instance(self.app.wsgi_app.wsgi_app, MyApplicationMiddleware)

        # Try adding second one
        self.app.middleware(MySecondApplicationMiddleware)

        # Check if registered
        self.assert_equal(1, self.app.extensions['edmunds.applicationmiddleware.middleware'].count(MyApplicationMiddleware))
        self.assert_equal(1, self.app.extensions['edmunds.applicationmiddleware.middleware'].count(MySecondApplicationMiddleware))
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
