
from tests.testcase import TestCase
from sqlalchemy.engine.base import Engine


class TestMySql(TestCase):
    """
    Test MySql
    """

    def test_my_sql(self):
        """
        Test MySql
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
        engine = app.database()
        self.assert_is_not_none(engine)
        self.assert_is_instance(engine, Engine)
