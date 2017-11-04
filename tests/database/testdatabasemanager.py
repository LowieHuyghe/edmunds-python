
from tests.testcase import TestCase
from sqlalchemy.engine.base import Engine


class TestTable(TestCase):
    """
    Test the table
    """

    def test_multiple_binds(self):
        """
        Test multiple SQLAlchemy binds
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.database.drivers.mysql import MySql \n",
            "from edmunds.database.drivers.postgresql import PostgreSql \n",
            "from edmunds.database.drivers.sqlite import Sqlite \n",
            "from edmunds.storage.drivers.file import File \n",
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
            "           { \n",
            "               'name': 'postgresql',\n",
            "               'driver': PostgreSql,\n",
            "               'user': 'root',\n",
            "               'pass': 'root',\n",
            "               'host': 'localhost',\n",
            "               'database': 'edmunds',\n",
            "           }, \n",
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
        self.assert_is_instance(app.database_engine(), Engine)
        self.assert_is_instance(app.database_engine('mysql'), Engine)
        self.assert_is_instance(app.database_engine('postgresql'), Engine)
        self.assert_is_instance(app.database_engine('sqlite'), Engine)

        # Test SQLAlchemy config
        self.assert_equal('mysql://root:root@localhost:3306/edmunds', app.config('SQLALCHEMY_DATABASE_URI'))

        self.assert_equal(2, len(app.config('SQLALCHEMY_BINDS')))
        self.assert_not_in('mysql', app.config('SQLALCHEMY_BINDS'))
        self.assert_in('postgresql', app.config('SQLALCHEMY_BINDS'))
        self.assert_in('sqlite', app.config('SQLALCHEMY_BINDS'))

        self.assert_equal('postgresql://root:root@localhost:5432/edmunds', app.config('SQLALCHEMY_BINDS')['postgresql'])
        self.assert_equal('sqlite:///%s' % app.fs().path('sqlite.db'), app.config('SQLALCHEMY_BINDS')['sqlite'])
