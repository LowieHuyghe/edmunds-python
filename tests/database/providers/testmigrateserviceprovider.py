
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

    def test_override_models(self):
        """
        Test override models
        :return:    void
        """

        module_name = 'zoslkuenddzomycustomtesttable'
        submodule_name = 'lkozkdozdmycustomtestsubtable'
        submodels_package = 'submodels'

        # Make models directory structure
        models_dir = self.temp_dir(only_path=True)
        submodels_dir = os.path.join(models_dir, submodels_package)
        os.mkdir(models_dir)
        os.mkdir(submodels_dir)
        # Make __init__.py files
        with open(os.path.join(models_dir, '__init__.py'), 'w') as init_file:
            init_file.write('')
        with open(os.path.join(submodels_dir, '__init__.py'), 'w') as init_file:
            init_file.write('')
        # Make class-files
        with open(os.path.join(models_dir, '%s.py' % module_name), 'w') as table_file:
            table_file.writelines([
                "class TestModel(object):\n",
                "    pass"
            ])
        with open(os.path.join(submodels_dir, '%s.py' % submodule_name), 'w') as table_file:
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
            "       'models': [ \n",
            "           '%s',\n" % models_dir,
            "       ],\n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()

        # Test modules
        self.assert_false(module_name in sys.modules)
        self.assert_false('%s.%s' % (submodels_package, submodule_name) in sys.modules)

        # Register
        app.register(MigrateServiceProvider)

        # Test modules
        self.assert_true(module_name in sys.modules)
        self.assert_true('%s.%s' % (submodels_package, submodule_name) in sys.modules)
