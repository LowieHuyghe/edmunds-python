
from tests.testcase import TestCase
from sqlalchemy.engine.base import Engine
from sqlalchemy.orm.scoping import scoped_session


class TestDatabase(TestCase):
    """
    Test the Database
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

        # Test database
        self.assert_is_none(app.database())
        self.assert_is_none(app.database('mysql'))
        self.assert_is_none(app.database('mysql2'))

        # Test session
        self.assert_is_none(app.database_session())
        self.assert_is_none(app.database_session('mysql'))
        self.assert_is_none(app.database_session('mysql2'))

    def test_loading_and_database_and_session(self):
        """
        Test loading and database and session function
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

        # Test database
        self.assert_is_not_none(app.database())
        self.assert_is_instance(app.database(), Engine)
        self.assert_is_not_none(app.database('mysql'))
        self.assert_is_instance(app.database('mysql'), Engine)
        with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
            app.database('mysql2')
        self.assert_is_none(app.database('mysql2', no_instance_error=True))

        self.assert_equal_deep(app.database(), app.database())
        self.assert_equal_deep(app.database(), app.database('mysql'))

        # Test session
        self.assert_is_not_none(app.database_session())
        self.assert_is_instance(app.database_session(), scoped_session)
        self.assert_is_not_none(app.database_session('mysql'))
        self.assert_is_instance(app.database_session('mysql'), scoped_session)
        with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
            app.database_session('mysql2')
        self.assert_is_none(app.database_session('mysql2', no_instance_error=True))

        self.assert_equal_deep(app.database_session(), app.database_session())
        self.assert_equal_deep(app.database_session(), app.database_session('mysql'))
