
from tests.testcase import TestCase
from edmunds.foundation.debugmiddleware import DebugMiddleware


class TestDebugMiddleware(TestCase):
    """
    Test the Debug Middleware
    """

    cache = None

    def set_up(self):
        """
        Set up the test case
        """

        super(TestDebugMiddleware, self).set_up()

        TestDebugMiddleware.cache = dict()
        TestDebugMiddleware.cache['timeline'] = []

    def test_registering(self):
        """
        Test registering the application middleware
        """

        # Check empty
        self.assert_not_in('edmunds.applicationmiddleware.middleware', self.app.extensions)

        # Register the middleware
        self.app.middleware(MyDebugMiddleware)

        # Check if registered
        self.assert_equal(1, self.app.extensions['edmunds.applicationmiddleware.middleware'].count(MyDebugMiddleware))
        self.assert_is_instance(self.app.wsgi_app, MyDebugMiddleware)
        self.assert_not_is_instance(self.app.wsgi_app.wsgi_app, MyDebugMiddleware)

        # Try adding it again
        self.app.middleware(MyDebugMiddleware)

        # Check if duplicate
        self.assert_equal(1, self.app.extensions['edmunds.applicationmiddleware.middleware'].count(MyDebugMiddleware))
        self.assert_is_instance(self.app.wsgi_app, MyDebugMiddleware)
        self.assert_not_is_instance(self.app.wsgi_app.wsgi_app, MyDebugMiddleware)


class MyDebugMiddleware(DebugMiddleware):
    """
    Debug Middleware class
    """

    def handle(self, environment, start_response):

        TestDebugMiddleware.cache['timeline'].append(self.__class__.__name__)

        return super(MyDebugMiddleware, self).handle(environment, start_response)
