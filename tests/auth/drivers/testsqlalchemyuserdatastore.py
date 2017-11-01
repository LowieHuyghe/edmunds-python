
from tests.testcase import TestCase
from flask_security import Security, SQLAlchemyUserDatastore
from edmunds.auth.models.usermixin import UserMixin
from edmunds.auth.models.rolemixin import RoleMixin
from edmunds.database.databasemanager import DatabaseManager


class TestSQLAlchemyUserDatastore(TestCase):

    def set_up(self):
        """
        Set up
        :return:    void
        """
        super(TestSQLAlchemyUserDatastore, self).set_up()

        self.valid_config_import_offset = 18
        self.valid_config = [
            "from edmunds.database.drivers.sqlitememory import SqliteMemory \n",
            "from flask_security import SQLAlchemyUserDatastore \n",
            "from edmunds.database.db import db, relationship, backref \n",
            "from edmunds.auth.models.usermixin import UserMixin \n",
            "from edmunds.auth.models.rolemixin import RoleMixin \n",
            " \n",
            "UserRolesTable = db.Table( \n",
            "    'user_roles', \n",
            "    db.Column('user_id', db.Integer, db.ForeignKey('user.id')), \n",
            "    db.Column('role_id', db.Integer, db.ForeignKey('role.id')), \n",
            ") \n",
            " \n",
            "class Role(db.Model, RoleMixin): \n",
            "    pass \n",
            " \n",
            "class User(db.Model, UserMixin): \n",
            "    roles = relationship(Role, backref=backref('users', lazy='dynamic'), secondary=UserRolesTable) \n",
            " \n",
            "APP = { \n",
            "   'auth': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'authsqlalchemy',\n",
            "               'driver': SQLAlchemyUserDatastore,\n",
            "               'models': {\n",
            "                   'user': User, \n",
            "                   'role': Role, \n",
            "               },\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
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
        ]

    def test_disabled(self):
        """
        Test disabled
        :return:    void
        """

        config = self.valid_config[:]
        config[self.valid_config_import_offset + 2] = "       'enabled': False, \n"
        self.write_config(config)

        DatabaseManager._sql_alchemy_instance = None
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
        config[self.valid_config_import_offset + 15] = "       'enabled': False, \n"
        self.write_config(config)

        DatabaseManager._sql_alchemy_instance = None
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

        missing_config_lines = [self.valid_config_import_offset + 8, self.valid_config_import_offset + 9]

        for missing_config_line in missing_config_lines:
            config = self.valid_config[:]
            del config[missing_config_line]
            self.write_config(config)

            DatabaseManager._sql_alchemy_instance = None
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

        DatabaseManager._sql_alchemy_instance = None
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

        self.assert_is_instance(app.auth_userdatastore().user_model(), UserMixin)
        self.assert_is_instance(app.auth_userdatastore().role_model(), RoleMixin)
