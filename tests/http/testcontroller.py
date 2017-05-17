
from tests.testcase import TestCase
from edmunds.http.controller import Controller
from edmunds.http.input import Input
from flask import request


class TestController(TestCase):
    """
    Test the Controller
    """

    def test_absolutely_nothing(self):
        """
        Test absolutely nothing
        :return:    void
        """

        self.assert_is_instance(MyController(self.app), MyController)

    def test_request_context(self):
        """
        Test request context
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Call route
        with self.app.test_request_context(rule):
            controller = MyController(self.app)

            self.assert_is_instance(controller, MyController)
            self.assert_equal(controller._app, self.app)
            self.assert_equal(controller._request, request)
            self.assert_is_instance(controller._input, Input)


class MyController(Controller):
    """
    Controller class
    """

    def initialize(self):

        return super(MyController, self).initialize()
