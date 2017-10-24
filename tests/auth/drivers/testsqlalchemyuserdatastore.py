
from tests.testcase import TestCase
from flask_security import Security, SQLAlchemyUserDatastore
from edmunds.database.model import relationship, backref, Column, String, Table, Integer, ForeignKey
from edmunds.auth.models.usermixin import UserMixin
from edmunds.auth.models.rolemixin import RoleMixin
from edmunds.database.model import Model


class TestSQLAlchemyUserDatastore(TestCase):

    def set_up(self):
        """
        Set up
        :return:    void
        """
        super(TestSQLAlchemyUserDatastore, self).set_up()

        self.valid_config_import_offset = 4
        self.valid_config = [
            "from edmunds.database.drivers.mysql import MySql \n",
            "from flask_security import SQLAlchemyUserDatastore \n",
            "from tests.auth.drivers.testsqlalchemyuserdatastore import TestSQLAlchemyUserDatastoreUser \n",
            "from tests.auth.drivers.testsqlalchemyuserdatastore import TestSQLAlchemyUserDatastoreRole \n",
            "APP = { \n",
            "   'auth': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'authsqlalchemy',\n",
            "               'driver': SQLAlchemyUserDatastore,\n",
            "               'models': {\n",
            "                   'user': TestSQLAlchemyUserDatastoreUser,\n",
            "                   'role': TestSQLAlchemyUserDatastoreRole,\n",
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
        config[self.valid_config_import_offset + 2] = "       'enabled': False, \n"
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
        config[self.valid_config_import_offset + 15] = "       'enabled': False, \n"
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

        missing_config_lines = [self.valid_config_import_offset + 8, self.valid_config_import_offset + 9]

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

        self.assert_equal_deep(app.auth_userdatastore().user_model, TestSQLAlchemyUserDatastoreUser)
        self.assert_equal_deep(app.auth_userdatastore().role_model, TestSQLAlchemyUserDatastoreRole)


UserRolesTable = Table(
    'test_sql_alchemy_user_datastore_user_roles',
    Column('test_sql_alchemy_user_datastore_user_id', Integer, ForeignKey('test_sql_alchemy_user_datastore_user.id')),
    Column('test_sql_alchemy_user_datastore_role_id', Integer, ForeignKey('test_sql_alchemy_user_datastore_role.id')),
)


class TestSQLAlchemyUserDatastoreRole(Model, RoleMixin):
    extra_prop = Column(String(255))


class TestSQLAlchemyUserDatastoreUser(Model, UserMixin):
    extra_prop = Column(String(255))
    roles = relationship(TestSQLAlchemyUserDatastoreRole, backref=backref('users', lazy='dynamic'), secondary=UserRolesTable)
