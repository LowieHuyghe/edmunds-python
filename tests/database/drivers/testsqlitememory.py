
from tests.testcase import TestCase
from sqlalchemy.engine.base import Engine


class TestSqliteMemory(TestCase):
    """
    Test SqliteMemory
    """

    def test_sqlitememory(self):
        """
        Test SqliteMemory
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.database.drivers.sqlitememory import SqliteMemory \n",
            "APP = { \n",
            "   'database': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'sqlitememory',\n",
            "               'driver': SqliteMemory,\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()

        # Test database
        engine = app.database_engine()
        self.assert_is_not_none(engine)
        self.assert_is_instance(engine, Engine)

        # Test SQLAlchemy config
        self.assert_equal('sqlite://', app.config('SQLALCHEMY_DATABASE_URI'))

    def test_sqlitememory_production(self):
        """
        Test SqliteMemory in production
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.database.drivers.sqlitememory import SqliteMemory \n",
            "APP = { \n",
            "   'database': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'sqlitememory',\n",
            "               'driver': SqliteMemory,\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()
        app.debug = False
        app.testing = False

        # Test database
        with self.assert_raises_regexp(RuntimeError, 'SqliteMemory should not be used in non-debug and non-testing environment'):
            app.database_engine()
