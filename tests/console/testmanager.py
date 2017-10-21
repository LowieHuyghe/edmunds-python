
from tests.testcase import TestCase
from edmunds.console.manager import Manager
from edmunds.console.command import Command


class TestManager(TestCase):
    """
    Test manager
    """

    def test_no_abstract(self):
        """
        Test no abstract manager
        :return:    void
        """

        with self.assert_raises_regexp(TypeError, 'Can\'t instantiate abstract class'):
            MyManagerNoAbsstract(self.app)

    def test_manager(self):
        """
        Test manager
        :return:    void
        """

        manager = MyManager(self.app)

        self.assert_equal_deep(manager.app, self.app)
        self.assert_equal(0, len(self.app.cli.commands))

        manager.add_commands()
        self.assert_equal(1, len(self.app.cli.commands))
        self.assert_in('testje', self.app.cli.commands)


class MyManagerNoAbsstract(Manager):
    pass


class MyManager(Manager):
    """
    My Manager
    """
    def add_commands(self):
        """
        Add the commands
        :return:    void
        """
        self.add_command('testje', MyCommand)


class MyCommand(Command):
    """
    My Command
    """
    def run(self, *args, **kwargs):
        """
        Object
        :return:    void
        """
        pass
