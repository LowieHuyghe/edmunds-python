
from tests.testcase import TestCase
from edmunds.foundation.testing.testcommand import TestCommand
from edmunds.console.command import Command
import mock


class TestTestCommand(TestCase):
    """
    Test test command
    """

    def test_command(self):
        """
        Test command
        :return:    void
        """

        command = TestCommand()
        self.assert_is_instance(command, Command)

        with mock.patch('nose.run', return_value=None):
            command.run()
