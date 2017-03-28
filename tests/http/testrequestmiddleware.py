
from tests.testcase import TestCase
from edmunds.http.requestmiddleware import RequestMiddleware


class TestRequestMiddleware(TestCase):
    """
    Test the Request Middleware
    """

    def test_absolutely_nothing(self):
        """
        Test absolutely nothing
        :return:    void
        """

        self.assert_is_instance(MyRequestMiddleware(self.app), MyRequestMiddleware)


class MyRequestMiddleware(RequestMiddleware):
    """
    Request Middleware class
    """

    def before(self):

        return super(MyRequestMiddleware, self).before()

    def after(self, response):

        return super(MyRequestMiddleware, self).after(response)
