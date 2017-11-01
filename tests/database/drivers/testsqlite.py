
from tests.testcase import TestCase
from sqlalchemy.engine.base import Engine


class TestSqlite(TestCase):
    """
    Test Sqlite
    """

    def test_missing_params(self):
        """
        Test missing params
        :return:    void
        """

        config = [
            "from edmunds.database.drivers.sqlite import Sqlite \n",
            "APP = { \n",
            "   'database': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'sqlite',\n",
            "               'driver': Sqlite,\n",
            "               'file': 'sqlite.bd',\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ]
        remove_lines = [8]

        # Loop lines that should be individually removed
        for remove_line in remove_lines:
            new_config = config[:]
            del new_config[remove_line]

            self.write_config(new_config)

            # Create app
            app = self.create_application()

            # Error on loading of config
            with self.assert_raises_regexp(RuntimeError, 'missing some configuration'):
                app.database_engine()

    def test_postgre_sql(self):
        """
        Test Sqlite
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.database.drivers.sqlite import Sqlite \n",
            "from edmunds.storage.drivers.file import File \n",
            "APP = { \n",
            "   'database': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'sqlite',\n",
            "               'driver': Sqlite,\n",
            "               'file': 'sqlite.db',\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "   'storage': { \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'file',\n",
            "               'driver': File,\n",
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
        self.assert_equal('sqlite://%s' % app.fs().path('sqlite.db'), app.config('SQLALCHEMY_DATABASE_URI'))
