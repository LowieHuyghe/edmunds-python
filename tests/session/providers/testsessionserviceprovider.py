
from tests.testcase import TestCase
from flask.sessions import SecureCookieSession
from werkzeug.local import LocalProxy
from edmunds.session.sessionmanager import SessionManager


class TestSessionServiceProvider(TestCase):
    """
    Test the Session Service Provider
    """

    def test_not_enabled(self):
        """
        Test not enabled
        :return:    void
        """

        secret_key = self.rand_str(24)

        # Write config
        self.write_config([
            "from edmunds.session.drivers.sessioncookie import SessionCookie \n",
            "SECRET_KEY = '%s'\n" % secret_key,
            "APP = { \n",
            "   'session': { \n",
            "       'enabled': False, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'sessioncookie',\n",
            "               'driver': SessionCookie,\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
            ])

        # Create app
        app = self.create_application()
        self.assert_equal(secret_key, app.secret_key)

        # Test extension
        self.assert_not_in('edmunds.session', app.extensions)

    def test_outside_context(self):
        """
        Test outside context
        :return:    void
        """

        secret_key = self.rand_str(24)

        # Write config
        self.write_config([
            "from edmunds.session.drivers.sessioncookie import SessionCookie \n",
            "SECRET_KEY = '%s'\n" % secret_key,
            "APP = { \n",
            "   'session': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'sessioncookie',\n",
            "               'driver': SessionCookie,\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
            ])

        # Create app
        app = self.create_application()
        self.assert_equal(secret_key, app.secret_key)

        # Test extension
        self.assert_in('edmunds.session', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.session'])
        self.assert_is_instance(app.extensions['edmunds.session'], SessionManager)

        self.assert_is_instance(app.extensions['edmunds.session'].get(), LocalProxy)
        with self.assert_raises_regexp(RuntimeError, 'Working outside of request context'):
            self.assert_is_instance(app.extensions['edmunds.session'].get()._get_current_object(), SecureCookieSession)
        self.assert_is_instance(app.extensions['edmunds.session'].get('sessioncookie'), LocalProxy)
        with self.assert_raises_regexp(RuntimeError, 'Working outside of request context'):
            self.assert_is_instance(app.extensions['edmunds.session'].get('sessioncookie')._get_current_object(), SecureCookieSession)
        with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
            app.extensions['edmunds.session'].get('sessioncookie2')

    def test_register(self):
        """
        Test register
        :return:    void
        """

        rule = '/' + self.rand_str(20)
        secret_key = self.rand_str(24)

        # Write config
        self.write_config([
            "from edmunds.session.drivers.sessioncookie import SessionCookie \n",
            "SECRET_KEY = '%s'\n" % secret_key,
            "APP = { \n",
            "   'session': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'sessioncookie',\n",
            "               'driver': SessionCookie,\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
            ])

        # Create app
        app = self.create_application()
        self.assert_equal(secret_key, app.secret_key)

        # Test extension
        self.assert_in('edmunds.session', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.session'])
        self.assert_is_instance(app.extensions['edmunds.session'], SessionManager)

        # Test session
        with app.test_request_context(rule):
            self.assert_is_instance(app.extensions['edmunds.session'].get(), LocalProxy)
            self.assert_is_instance(app.extensions['edmunds.session'].get()._get_current_object(), SecureCookieSession)
            self.assert_is_instance(app.extensions['edmunds.session'].get('sessioncookie'), LocalProxy)
            self.assert_is_instance(app.extensions['edmunds.session'].get('sessioncookie')._get_current_object(), SecureCookieSession)
            with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
                app.extensions['edmunds.session'].get('sessioncookie2')
