
from tests.testcase import TestCase
from edmunds.console.command import Command


class TestCommand(TestCase):
    """
    Test command
    """

    def test_no_abstract(self):
        """
        Test no abstract command
        :return:    void
        """

        with self.assert_raises_regexp(TypeError, 'Can\'t instantiate abstract class'):
            MyCommandNoAbsstract('test', self.app)

    def test_command(self):
        """
        Test command
        :return:    void
        """
        command_name = 'test'

        command = MyCommand(command_name, self.app)

        self.assert_equal_deep(command.name, command_name)
        self.assert_equal_deep(command.app, self.app)


class MyCommandNoAbsstract(Command):
    pass


class MyCommand(Command):

    def run(self, *args, **kwargs):
        pass
