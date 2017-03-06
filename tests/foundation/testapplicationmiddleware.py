
from tests.testcase import TestCase
from edmunds.foundation.applicationmiddleware import ApplicationMiddleware
import edmunds.support.helpers as helpers


class TestApplicationMiddleware(TestCase):
    """
    Test the Application Middleware
    """

    cache = None

    def set_up(self):
        """
        Set up the test case
        """

        super(TestApplicationMiddleware, self).set_up()

        TestApplicationMiddleware.cache = dict()
        TestApplicationMiddleware.cache['timeline'] = []

    def test_no_abstract_handle(self):
        """
        Test if abstract handle method is required
        """

        with self.assert_raises_regexp(TypeError, 'handle'):
            MyApplicationMiddlewareNoAbstractHandle(self.app)

    def test_abstract_handle(self):
        """
        Test required abstract handle method
        """

        self.assert_is_instance(MyApplicationMiddlewareAbstractHandle(self.app), MyApplicationMiddlewareAbstractHandle)

    def test_registering(self):
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

    def test_handling(self):
        """
        Test handling of application middleware
        """

        # Register the middleware
        self.app.middleware(MyApplicationMiddleware)
        # Add it a second time to make sure it is only called once
        self.app.middleware(MyApplicationMiddleware)

        # Add route
        rule = '/' + helpers.random_str(20)
        @self.app.route(rule)
        def handleRoute():
            TestApplicationMiddleware.cache['timeline'].append('handleRoute')
            return ''

        # Call route
        with self.app.test_client() as c:
            rv = c.get(rule)

            self.assert_equal(2, len(TestApplicationMiddleware.cache['timeline']))

            self.assert_in(MyApplicationMiddleware.__name__, TestApplicationMiddleware.cache['timeline'])
            self.assert_equal(0, TestApplicationMiddleware.cache['timeline'].index(MyApplicationMiddleware.__name__))

            self.assert_in('handleRoute', TestApplicationMiddleware.cache['timeline'])
            self.assert_equal(1, TestApplicationMiddleware.cache['timeline'].index('handleRoute'))

        # Add second middleware
        self.app.middleware(MySecondApplicationMiddleware)

        # Call route
        TestApplicationMiddleware.cache = dict()
        TestApplicationMiddleware.cache['timeline'] = []
        with self.app.test_client() as c:
            rv = c.get(rule)

            self.assert_equal(3, len(TestApplicationMiddleware.cache['timeline']))

            self.assert_in(MySecondApplicationMiddleware.__name__, TestApplicationMiddleware.cache['timeline'])
            self.assert_equal(0, TestApplicationMiddleware.cache['timeline'].index(MySecondApplicationMiddleware.__name__))

            self.assert_in(MyApplicationMiddleware.__name__, TestApplicationMiddleware.cache['timeline'])
            self.assert_equal(1, TestApplicationMiddleware.cache['timeline'].index(MyApplicationMiddleware.__name__))

            self.assert_in('handleRoute', TestApplicationMiddleware.cache['timeline'])
            self.assert_equal(2, TestApplicationMiddleware.cache['timeline'].index('handleRoute'))


class MyApplicationMiddlewareNoAbstractHandle(ApplicationMiddleware):
    """
    Application Middleware class with missing handle method
    """

    pass


class MyApplicationMiddlewareAbstractHandle(ApplicationMiddleware):
    """
    Application Middleware class with handle method
    """

    def handle(self, environment, startResponse):
        pass


class MyApplicationMiddleware(ApplicationMiddleware):
    """
    Application Middleware class
    """

    def handle(self, environment, startResponse):

        TestApplicationMiddleware.cache['timeline'].append(self.__class__.__name__)

        return super(MyApplicationMiddleware, self).handle(environment, startResponse)


class MySecondApplicationMiddleware(ApplicationMiddleware):
    """
    Second Application Middleware class
    """

    def handle(self, environment, startResponse):

        TestApplicationMiddleware.cache['timeline'].append(self.__class__.__name__)

        return super(MySecondApplicationMiddleware, self).handle(environment, startResponse)
