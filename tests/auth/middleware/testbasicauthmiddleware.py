
from tests.testcase import TestCase
from edmunds.auth.middleware.basicauthmiddleware import BasicAuthMiddleware
from edmunds.database.model import db, relationship, backref, Column, String, Table, ForeignKey, Integer
from edmunds.auth.models.usermixin import UserMixin
from edmunds.auth.models.rolemixin import RoleMixin
from edmunds.database.model import Model
from werkzeug.exceptions import Unauthorized
from base64 import b64encode
from edmunds.encoding.encoding import Encoding


class TestBasicAuthMiddleware(TestCase):

    def set_up(self):
        """
        Set up the test case
        """

        super(TestBasicAuthMiddleware, self).set_up()

        self.valid_config_import_offset = 8
        self.valid_config = [
            "from edmunds.database.drivers.sqlitememory import SqliteMemory \n",
            "from flask_security import SQLAlchemyUserDatastore \n",
            "from tests.auth.middleware.testbasicauthmiddleware import TestBasicAuthMiddlewareUser \n",
            "from tests.auth.middleware.testbasicauthmiddleware import TestBasicAuthMiddlewareRole \n",
            "from edmunds.storage.drivers.file import File as StorageFile \n",
            "from edmunds.log.drivers.file import File \n",
            "SECURITY_PASSWORD_HASH = 'sha512_crypt' \n",
            "SECURITY_PASSWORD_SALT = 'thisisarandomsalt' \n",
            "APP = { \n",
            "   'auth': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'authsqlalchemy',\n",
            "               'driver': SQLAlchemyUserDatastore,\n",
            "               'models': {\n",
            "                   'user': TestBasicAuthMiddlewareUser,\n",
            "                   'role': TestBasicAuthMiddlewareRole,\n",
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
            "   'storage': { \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'file',\n",
            "               'driver': StorageFile,\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "   'log': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'file',\n",
            "               'driver': File,\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ]
        self.user_email = self.rand_str(7) + '@test.com'
        self.user_password = self.rand_str(10)

    def test_unauthorized(self):
        """
        Test unauthorized
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        self.write_config(self.valid_config)
        app = self.create_application()
        self.init_database(app)

        # Add route
        @app.route(rule, middleware=[BasicAuthMiddleware])
        def handle_route():
            return ''

        # Call route
        with app.test_client() as c:
            rv = c.get(rule)
            self.assert_equal(Unauthorized.code, rv.status_code, msg=rv.data)

    def test_authorized(self):
        """
        Test auth
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        self.write_config(self.valid_config)
        app = self.create_application()
        self.init_database(app)

        # Add route
        @app.route(rule, middleware=[BasicAuthMiddleware])
        def handle_route():
            return ''

        # Call route
        with app.test_client() as c:
            user_pass_b64 = '%s:%s' % (self.user_email, self.user_password)
            user_pass_b64 = user_pass_b64.encode()
            user_pass_b64 = Encoding.normalize(b64encode(user_pass_b64))
            headers = {'Authorization': 'Basic %s' % user_pass_b64}
            rv = c.get(rule, headers=headers)
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
        userdatastore.create_user(email=self.user_email, password=self.user_password)
        db.session.commit()


UserRolesTable = Table(
    'test_basic_auth_middleware_user_roles',
    Column('test_basic_auth_middleware_user_id', Integer, ForeignKey('test_basic_auth_middleware_user.id')),
    Column('test_basic_auth_middleware_role_id', Integer, ForeignKey('test_basic_auth_middleware_role.id')),
)


class TestBasicAuthMiddlewareRole(Model, RoleMixin):
    extra_prop = Column(String(255))


class TestBasicAuthMiddlewareUser(Model, UserMixin):
    extra_prop = Column(String(255))
    roles = relationship(TestBasicAuthMiddlewareRole, backref=backref('users', lazy='dynamic'), secondary=UserRolesTable)
