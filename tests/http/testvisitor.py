
from tests.testcase import TestCase
from edmunds.http.visitor import Visitor
from edmunds.globals import request
from user_agents.parsers import UserAgent


class TestVisitor(TestCase):
    """
    Test Visitor
    """

    def test_client(self):
        """
        Test client
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Call route
        with self.app.test_request_context(rule):
            visitor = Visitor(self.app, request)
            self.assert_is_instance(visitor.client, UserAgent)
