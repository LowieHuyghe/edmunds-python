
from tests.testcase import TestCase
from edmunds.console.manager import Manager
from edmunds.foundation.database.nomigratecommand import NoMigrateCommand
from edmunds.foundation.database.nomigratemanager import NoMigrateManager
from flask_migrate import MigrateCommand as FlaskMigrateCommand


class TestNoMigrateManager(TestCase):
    """
    Test the no migrate manager
    """

    def test_manager(self):
        """
        Test manager
        :return:    void
        """

        manager = NoMigrateManager(FlaskMigrateCommand)

        self.assert_is_instance(manager, Manager)

        for command_name in manager._commands:
            command_class = manager._commands[command_name].__class__

            self.assert_in(command_class, [NoMigrateManager, NoMigrateCommand])
