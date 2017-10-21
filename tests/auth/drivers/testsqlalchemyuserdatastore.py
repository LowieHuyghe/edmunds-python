
from tests.testcase import TestCase
from flask_security import Security, SQLAlchemyUserDatastore
from edmunds.auth.models.user import User
from edmunds.auth.models.role import Role


class TestSQLAlchemyUserDatastore(TestCase):

    def set_up(self):
        """
        Set up
        :return:    void
        """
        super(TestSQLAlchemyUserDatastore, self).set_up()

        self.valid_config = [
            "from edmunds.database.drivers.mysql import MySql \n",
            "from flask_security import SQLAlchemyUserDatastore \n",
            "from edmunds.auth.models.user import User \n",
            "from edmunds.auth.models.role import Role \n",
            "APP = { \n",
            "   'auth': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'authsqlalchemy',\n",
            "               'driver': SQLAlchemyUserDatastore,\n",
            "               'models': {\n",
            "                   'user': User,\n",
            "                   'role': Role,\n",
            "               },\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
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
        ]

    def test_disabled(self):
        """
        Test disabled
        :return:    void
        """

        config = self.valid_config[:]
        config[6] = "       'enabled': False, \n"
        self.write_config(config)

        app = self.create_application()

        self.assert_is_none(app.auth_security())
        self.assert_is_none(app.auth_security('authsqlalchemy'))
        self.assert_is_none(app.auth_userdatastore())
        self.assert_is_none(app.auth_userdatastore('authsqlalchemy'))

    def test_database_disabled(self):
        """
        Test disabled
        :return:    void
        """

        config = self.valid_config[:]
        config[19] = "       'enabled': False, \n"
        self.write_config(config)

        app = self.create_application()

        with self.assert_raises_regexp(RuntimeError, 'Auth requires database to be enabled'):
            app.auth_security()
        with self.assert_raises_regexp(RuntimeError, 'Auth requires database to be enabled'):
            app.auth_security('authsqlalchemy')
        with self.assert_raises_regexp(RuntimeError, 'Auth requires database to be enabled'):
            app.auth_userdatastore()
        with self.assert_raises_regexp(RuntimeError, 'Auth requires database to be enabled'):
            app.auth_userdatastore('authsqlalchemy')

    def test_missing_config(self):
        """
        Test missing config
        :return:    void
        """

        missing_config_lines = [12, 13]

        for missing_config_line in missing_config_lines:
            config = self.valid_config[:]
            del config[missing_config_line]
            self.write_config(config)

            app = self.create_application()

            with self.assert_raises_regexp(RuntimeError, '\'authsqlalchemy\' is missing some configuration'):
                app.auth_security()
            with self.assert_raises_regexp(RuntimeError, '\'authsqlalchemy\' is missing some configuration'):
                app.auth_security('authsqlalchemy')
            with self.assert_raises_regexp(RuntimeError, '\'authsqlalchemy\' is missing some configuration'):
                app.auth_userdatastore()
            with self.assert_raises_regexp(RuntimeError, '\'authsqlalchemy\' is missing some configuration'):
                app.auth_userdatastore('authsqlalchemy')

    def test_driver(self):
        """
        Test driver
        :return:    void
        """

        self.write_config(self.valid_config)

        app = self.create_application()

        self.assert_is_not_none(app.auth_security())
        self.assert_is_instance(app.auth_security(), Security)
        self.assert_is_not_none(app.auth_security('authsqlalchemy'))
        self.assert_is_instance(app.auth_security('authsqlalchemy'), Security)

        self.assert_is_not_none(app.auth_userdatastore())
        self.assert_is_instance(app.auth_userdatastore(), SQLAlchemyUserDatastore)
        self.assert_is_not_none(app.auth_userdatastore('authsqlalchemy'))
        self.assert_is_instance(app.auth_userdatastore('authsqlalchemy'), SQLAlchemyUserDatastore)

        self.assert_equal_deep(app.auth_userdatastore(), app.auth_security().datastore)
        self.assert_equal_deep(app, app.auth_security().app)

        self.assert_equal_deep(User, app.auth_userdatastore().user_model)
        self.assert_equal_deep(Role, app.auth_userdatastore().role_model)
