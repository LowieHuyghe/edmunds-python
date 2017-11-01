
from tests.testcase import TestCase
from edmunds.auth.middleware.rolesrequiredmiddleware import RolesRequiredMiddleware
from edmunds.auth.middleware.basicauthmiddleware import BasicAuthMiddleware
from edmunds.database.model import db
from werkzeug.exceptions import Unauthorized, Forbidden
from base64 import b64encode
from edmunds.encoding.encoding import Encoding
from edmunds.database.databasemanager import DatabaseManager


class TestRolesRequiredMiddleware(TestCase):

    def set_up(self):
        """
        Set up the test case
        """

        super(TestRolesRequiredMiddleware, self).set_up()

        self.valid_config_import_offset = 23
        self.valid_config = [
            "from edmunds.database.drivers.sqlitememory import SqliteMemory \n",
            "from flask_security import SQLAlchemyUserDatastore \n",
            "from edmunds.database.model import db, relationship, backref \n",
            "from edmunds.auth.models.usermixin import UserMixin \n",
            "from edmunds.auth.models.rolemixin import RoleMixin \n",
            "from edmunds.storage.drivers.file import File as StorageFile \n",
            "from edmunds.log.drivers.file import File \n",
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
            "SECURITY_PASSWORD_HASH = 'sha512_crypt' \n",
            "SECURITY_PASSWORD_SALT = 'thisisarandomsalt' \n",
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
            # "   'storage': { \n",
            # "       'instances': [ \n",
            # "           { \n",
            # "               'name': 'file',\n",
            # "               'driver': StorageFile,\n",
            # "           }, \n",
            # "       ], \n",
            # "   }, \n",
            # "   'log': { \n",
            # "       'enabled': True, \n",
            # "       'instances': [ \n",
            # "           { \n",
            # "               'name': 'file',\n",
            # "               'driver': File,\n",
            # "           }, \n",
            # "       ], \n",
            # "   }, \n",
            "} \n",
        ]
        self.user_email = self.rand_str(7) + '@test.com'
        self.user_password = self.rand_str(10)
        self.user_email_no_roles = self.rand_str(7) + '@test2.com'
        self.user_password_no_roles = self.rand_str(10)
        self.user_email_some_roles = self.rand_str(7) + '@test3.com'
        self.user_password_some_roles = self.rand_str(10)
        self.role_name = self.rand_str(7) + 'testrole'
        self.role_second_name = self.rand_str(7) + 'testrole2'

    def test_no_user_check(self):
        """
        Test no user check
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        self.write_config(self.valid_config)
        DatabaseManager._sql_alchemy_instance = None
        app = self.create_application()
        self.init_database(app)

        # Add route
        @app.route(rule, middleware=[(RolesRequiredMiddleware, self.role_name)])
        def handle_route():
            return ''

        # Call route
        with app.test_client() as c:
            rv = c.get(rule)
            self.assert_equal(Forbidden.code, rv.status_code, msg=rv.data)

    def test_unauthorized(self):
        """
        Test unauthorized
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        self.write_config(self.valid_config)
        DatabaseManager._sql_alchemy_instance = None
        app = self.create_application()
        self.init_database(app)

        # Add route
        @app.route(rule, middleware=[BasicAuthMiddleware, (RolesRequiredMiddleware, self.role_name)])
        def handle_route():
            return ''

        # Call route
        with app.test_client() as c:
            rv = c.get(rule)
            self.assert_equal(Unauthorized.code, rv.status_code, msg=rv.data)

    def test_forbidden(self):
        """
        Test forbidden
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        self.write_config(self.valid_config)
        DatabaseManager._sql_alchemy_instance = None
        app = self.create_application()
        self.init_database(app)

        # Add route
        @app.route(rule, middleware=[BasicAuthMiddleware, (RolesRequiredMiddleware, self.role_name)])
        def handle_route():
            return ''

        # Call route
        with app.test_client() as c:
            rv = self.get_response(c, rule, self.user_email_no_roles, self.user_password_no_roles)
            self.assert_equal(Forbidden.code, rv.status_code, msg=rv.data)

    def test_some_forbidden(self):
        """
        Test some forbidden
        :return:    void
        """

        rule_1 = '/' + self.rand_str(20)
        rule_2 = '/' + self.rand_str(20)
        rule_3 = '/' + self.rand_str(20)

        self.write_config(self.valid_config)
        DatabaseManager._sql_alchemy_instance = None
        app = self.create_application()
        self.init_database(app)

        # Add route
        @app.route(rule_1, middleware=[BasicAuthMiddleware, (RolesRequiredMiddleware, self.role_name)])
        def handle_route_1():
            return ''

        @app.route(rule_2, middleware=[BasicAuthMiddleware, (RolesRequiredMiddleware, self.role_second_name)])
        def handle_route_2():
            return ''

        @app.route(rule_3, middleware=[BasicAuthMiddleware, (RolesRequiredMiddleware, self.role_name, self.role_second_name)])
        def handle_route_3():
            return ''

        # Call route
        with app.test_client() as c:
            rv = self.get_response(c, rule_1, self.user_email_some_roles, self.user_password_some_roles)
            self.assert_equal(200, rv.status_code, msg=rv.data)

            rv = self.get_response(c, rule_2, self.user_email_some_roles, self.user_password_some_roles)
            self.assert_equal(Forbidden.code, rv.status_code, msg=rv.data)

            rv = self.get_response(c, rule_3, self.user_email_some_roles, self.user_password_some_roles)
            self.assert_equal(Forbidden.code, rv.status_code, msg=rv.data)

    def test_authorized(self):
        """
        Test auth
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        self.write_config(self.valid_config)
        DatabaseManager._sql_alchemy_instance = None
        app = self.create_application()
        self.init_database(app)

        # Add route
        @app.route(rule, middleware=[BasicAuthMiddleware, (RolesRequiredMiddleware, self.role_name)])
        def handle_route():
            return ''

        # Call route
        with app.test_client() as c:
            rv = self.get_response(c, rule, self.user_email, self.user_password)
            self.assert_equal(200, rv.status_code, msg=rv.data)

    def init_database(self, app):
        """
        Initiate the database
        :param app: The application
        :type app:  edmunds.application.Application
        :return:    void
        """

        # Load all
        app.database_engine()

        # Create tables
        db.create_all(app=app)

        # Create user
        userdatastore = app.auth_userdatastore()

        role = userdatastore.create_role(name=self.role_name)
        role2 = userdatastore.create_role(name=self.role_second_name)

        userdatastore.create_user(email=self.user_email_no_roles, password=self.user_password_no_roles)
        user_some_roles = userdatastore.create_user(email=self.user_email_some_roles, password=self.user_password_some_roles)
        user_all_roles = userdatastore.create_user(email=self.user_email, password=self.user_password)

        userdatastore.add_role_to_user(user=user_some_roles, role=role)
        userdatastore.add_role_to_user(user=user_all_roles, role=role)
        userdatastore.add_role_to_user(user=user_all_roles, role=role2)

        db.session.commit()

    def get_response(self, client, rule, email, password):
        """
        Get response
        :param client:      The client
        :param rule:        The rule
        :param email:       The email to log in with
        :param password:    The password
        :return:            The response
        """

        user_pass_b64 = '%s:%s' % (email, password)
        user_pass_b64 = user_pass_b64.encode()
        user_pass_b64 = Encoding.normalize(b64encode(user_pass_b64))
        headers = {'Authorization': 'Basic %s' % user_pass_b64}
        return client.get(rule, headers=headers)
