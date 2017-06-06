
from tests.testcase import TestCase
from edmunds.console.manager import Manager
from flask_script import Manager as FlaskManager


class TestManager(TestCase):
    """
    Test manager
    """

    def test_manager(self):
        """
        Test manager
        :return:    void
        """

        manager = Manager()
        self.assert_is_instance(manager, FlaskManager)
