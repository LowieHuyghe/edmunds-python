
from tests.testcase import TestCase
from edmunds.auth.middleware.tokenauthmiddleware import TokenAuthMiddleware
from edmunds.database.model import db
from werkzeug.exceptions import Unauthorized
from edmunds.database.databasemanager import DatabaseManager
import json


class TestTokenAuthMiddleware(TestCase):

    def set_up(self):
        """
        Set up the test case
        """

        super(TestTokenAuthMiddleware, self).set_up()

        self.valid_config_import_offset = 25
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
            "SECRET_KEY = 'thisisarandomsecretkey' \n",
            "WTF_CSRF_ENABLED = False \n",
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
        @app.route(rule, middleware=[TokenAuthMiddleware])
        def handle_route():
            return ''

        # Call route
        with app.test_client() as c:
            rv = c.get(rule)
            self.assert_equal(Unauthorized.code, rv.status_code, msg=rv.data)

    def test_invalid_token(self):
        """
        Test invalid_token
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        self.write_config(self.valid_config)
        DatabaseManager._sql_alchemy_instance = None
        app = self.create_application()
        self.init_database(app)

        # Add route
        @app.route(rule, middleware=[TokenAuthMiddleware])
        def handle_route():
            return ''

        # Call route
        with app.test_client() as c:
            headers = {'Authentication-Token': self.rand_str(10)}
            rv = c.get(rule, headers=headers)
            self.assert_equal(Unauthorized.code, rv.status_code, msg=rv.data)

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
        @app.route(rule, middleware=[TokenAuthMiddleware])
        def handle_route():
            return ''

        # Call route
        with app.test_client() as c:
            data = json.dumps({'email': self.user_email, 'password': self.user_password})
            headers = {'content-type': 'application/json'}
            token_response = c.post('/login', data=data, headers=headers)
            token = json.loads(token_response.data)['response']['user']['authentication_token']

            headers = {'Authentication-Token': token}
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
