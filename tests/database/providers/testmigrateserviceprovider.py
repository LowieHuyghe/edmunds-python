
from tests.testcase import TestCase
from flask_migrate import Migrate
from edmunds.database.providers.migrateserviceprovider import MigrateServiceProvider
import sys
import os


class TestMigrationServiceProvider(TestCase):
    """
    Test the Migration Service Provider
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
            "               'database': 'edmunds',\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
            ])

        # Create app
        app = self.create_application()

        # Test extension
        self.assert_not_in('edmunds.database.migrate', app.extensions)

        # Register
        app.register(MigrateServiceProvider)

        # Test extension
        self.assert_not_in('edmunds.database.migrate', app.extensions)

    def test_register(self):
        """
        Test register
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
            "               'database': 'edmunds',\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
            ])

        # Create app
        app = self.create_application()

        # Test extension
        self.assert_in('edmunds.database', app.extensions)
        self.assert_is_none(app.extensions['edmunds.database']._instances)
        self.assert_not_in('edmunds.database.migrate', app.extensions)

        # Register
        app.register(MigrateServiceProvider)

        # Test extension
        self.assert_in('edmunds.database.migrate', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.database.migrate'])
        self.assert_is_instance(app.extensions['edmunds.database.migrate'], Migrate)
        self.assert_is_not_none(app.extensions['edmunds.database']._instances)
