
from tests.testcase import TestCase
from edmunds.http.response import Response
from flask.wrappers import Response as FlaskResponse


class TestResponse(TestCase):
    """
    Test Response
    """

    def test_response(self):
        """
        Test response
        :return:    void
        """

        response = Response()
        self.assert_is_instance(response, Response)
        self.assert_is_instance(response, FlaskResponse)
