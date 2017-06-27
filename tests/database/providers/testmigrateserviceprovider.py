
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
        self.assert_not_in('edmunds.database.manager', app.extensions)

        # Register
        app.register(MigrateServiceProvider)

        # Test extension
        self.assert_not_in('edmunds.database.manager', app.extensions)

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
        self.assert_not_in('edmunds.database.manager', app.extensions)

        # Register
        app.register(MigrateServiceProvider)

        # Test extension
        self.assert_in('edmunds.database.manager', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.database.manager'])
        self.assert_is_instance(app.extensions['edmunds.database.manager'], Migrate)

    def test_override_tables(self):
        """
        Test override tables
        :return:    void
        """

        module_name = 'zoslkuenddzomycustomtesttable'
        submodule_name = 'lkozkdozdmycustomtestsubtable'
        subtables_package = 'subtables'

        # Make tables directory structure
        tables_dir = self.temp_dir(only_path=True)
        subtables_dir = os.path.join(tables_dir, subtables_package)
        os.mkdir(tables_dir)
        os.mkdir(subtables_dir)
        # Make __init__.py files
        with open(os.path.join(tables_dir, '__init__.py'), 'w') as init_file:
            init_file.write('')
        with open(os.path.join(subtables_dir, '__init__.py'), 'w') as init_file:
            init_file.write('')
        # Make class-files
        with open(os.path.join(tables_dir, '%s.py' % module_name), 'w') as table_file:
            table_file.writelines([
                "class TestModel(object):\n",
                "    pass"
            ])
        with open(os.path.join(subtables_dir, '%s.py' % submodule_name), 'w') as table_file:
            table_file.writelines([
                "class TestSubModel(object):\n",
                "    pass"
            ])

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
            "       'tables': [ \n",
            "           '%s',\n" % tables_dir,
            "       ],\n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()

        # Test modules
        self.assert_false(module_name in sys.modules)
        self.assert_false('%s.%s' % (subtables_package, submodule_name) in sys.modules)

        # Register
        app.register(MigrateServiceProvider)

        # Test modules
        self.assert_true(module_name in sys.modules)
        self.assert_true('%s.%s' % (subtables_package, submodule_name) in sys.modules)
