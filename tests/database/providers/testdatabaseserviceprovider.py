
from tests.testcase import TestCase
from edmunds.database.databasemanager import DatabaseManager
from sqlalchemy.engine.base import Engine
from edmunds.globals import g


class TestDatabaseServiceProvider(TestCase):
    """
    Test the Database Service Provider
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
        self.assert_not_in('edmunds.database', app.extensions)
        self.assert_not_in('edmunds.database.sessions', app.extensions)

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
        self.assert_is_not_none(app.extensions['edmunds.database'])
        self.assert_is_instance(app.extensions['edmunds.database'], DatabaseManager)

        # Test database
        self.assert_is_not_none(app.extensions['edmunds.database'].get())
        self.assert_is_instance(app.extensions['edmunds.database'].get(), Engine)
        self.assert_is_not_none(app.extensions['edmunds.database'].get('mysql'))
        self.assert_is_instance(app.extensions['edmunds.database'].get('mysql'), Engine)
        with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
            app.extensions['edmunds.database'].get('mysql2')

        # Test sessions
        self.assert_in('edmunds.database.sessions', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.database.sessions'])
        self.assert_is_instance(app.extensions['edmunds.database.sessions'], dict)

    def test_removal_of_sessions(self):
        """
        Test removal of sessions
        :return:    void
        """

        rule = self.rand_str(20)

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

        with app.test_request_context(rule):
            # Make session
            Session = app.database_session()

            # Test
            self.assert_equal(1, len(getattr(g, 'edmunds_database_sessions', {})))
            self.assert_equal_deep(Session, list(getattr(g, 'edmunds_database_sessions', {}).values())[0])
            self.assert_false(Session.registry.has())

            # Simulate a call to db
            Session.registry.set(RegistryDummy())
            self.assert_true(Session.registry.has())

        # Closed session
        self.assert_false(Session.registry.has())

        with app.test_request_context(rule):
            # Make session 2
            Session2 = app.database_session()

            # Test
            self.assert_equal(1, len(getattr(g, 'edmunds_database_sessions', {})))
            self.assert_equal_deep(Session2, list(getattr(g, 'edmunds_database_sessions', {}).values())[0])
            self.assert_not_equal(Session, Session2)
            self.assert_false(Session2.registry.has())

            # Simulate a call to db
            Session2.registry.set(RegistryDummy())
            self.assert_true(Session2.registry.has())

        # Closed sessions
        self.assert_false(Session2.registry.has())
        self.assert_false(Session.registry.has())


class RegistryDummy(object):
    def close(self):
        pass
