
from tests.testcase import TestCase
from edmunds.console.command import Command, Option
from flask_script import Command as FlaskCommand, Option as FlaskOption


class TestCommand(TestCase):
    """
    Test command
    """

    def test_command(self):
        """
        Test command
        :return:    void
        """

        command = Command()
        self.assert_is_instance(command, FlaskCommand)

    def test_option(self):
        """
        Test option
        :return:    void
        """

        option = Option()
        self.assert_is_instance(option, FlaskOption)
