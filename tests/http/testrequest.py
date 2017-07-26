

from tests.testcase import TestCase
from edmunds.http.request import Request
from edmunds.globals import request
from werkzeug.local import LocalProxy


class TestRequest(TestCase):
    """
    Test Request
    """

    def test_request(self):
        """
        Test request
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Call route
        with self.app.test_request_context(rule):
            self.assert_is_not_none(request)
            self.assert_is_instance(request, LocalProxy)
            self.assert_is_instance(request._get_current_object(), Request)
