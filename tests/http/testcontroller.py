
from tests.testcase import TestCase
from edmunds.http.controller import Controller


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


class MyController(Controller):
    """
    Controller class
    """

    def initialize(self):

        return super(MyController, self).initialize()
