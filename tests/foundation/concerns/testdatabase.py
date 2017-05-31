
from tests.testcase import TestCase
from sqlalchemy.engine.base import Engine


class TestDatabase(TestCase):
    """
    Test the Database
    """

    def test_loading_and_database(self):
        """
        Test loading and database function
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

        # Test database
        self.assert_is_not_none(app.database())
        self.assert_is_instance(app.database(), Engine)
        self.assert_is_not_none(app.database('mysql'))
        self.assert_is_instance(app.database('mysql'), Engine)
        with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
            app.database('mysql2')
