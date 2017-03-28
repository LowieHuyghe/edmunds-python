
from tests.testcase import TestCase
from edmunds.foundation.applicationmiddleware import ApplicationMiddleware


class TestApplicationMiddleware(TestCase):
    """
    Test the Application Middleware
    """

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


class MyApplicationMiddlewareNoAbstractHandle(ApplicationMiddleware):
    """
    Application Middleware class with missing handle method
    """

    pass


class MyApplicationMiddlewareAbstractHandle(ApplicationMiddleware):
    """
    Application Middleware class with handle method
    """

    def handle(self, environment, start_response):
        pass
