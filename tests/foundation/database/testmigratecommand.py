
from tests.testcase import TestCase
from edmunds.foundation.database.migratecommand import MigrateCommand
from edmunds.foundation.database.nomigratemanager import NoMigrateManager
from flask_migrate import MigrateCommand as FlaskMigrateCommand


class TestMigrateCommand(TestCase):
    """
    Test the migrate command
    """

    def test_not_enabled(self):
        """
        Test not enabled
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.database.drivers.mysql import MySql \n",
            "APP = { \n",
            "   'database': { \n",
            "       'enabled': False, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'mysql',\n",
            "               'driver': MySql,\n",
            "               'user': 'root',\n",
            "               'pass': 'root',\n",
            "               'host': 'localhost',\n",
            "               'table': 'edmunds',\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()

        migrate_command = MigrateCommand(app)

        self.assert_is_instance(migrate_command, NoMigrateManager)
        self.assert_not_equal(FlaskMigrateCommand, migrate_command)

    def test_enabled(self):
        """
        Test enabled
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.database.drivers.mysql import MySql \n",
            "APP = { \n",
            "   'database': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'mysql',\n",
            "               'driver': MySql,\n",
            "               'user': 'root',\n",
            "               'pass': 'root',\n",
            "               'host': 'localhost',\n",
            "               'table': 'edmunds',\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()

        migrate_command = MigrateCommand(app)

        self.assert_not_is_instance(migrate_command, NoMigrateManager)
        self.assert_equal_deep(FlaskMigrateCommand, migrate_command)
