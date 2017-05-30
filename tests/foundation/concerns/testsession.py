
from tests.testcase import TestCase
from flask.sessions import SecureCookieSession
from werkzeug.local import LocalProxy


class TestSession(TestCase):
    """
    Test the Session
    """

    def test_loading_and_session(self):
        """
        Test loading and session function
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

        # Test session
        with app.test_request_context(rule):
            self.assert_is_instance(app.session(), LocalProxy)
            self.assert_is_instance(app.session()._get_current_object(), SecureCookieSession)
            self.assert_is_instance(app.session('sessioncookie'), LocalProxy)
            self.assert_is_instance(app.session('sessioncookie')._get_current_object(), SecureCookieSession)
            with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
                app.session('sessioncookie2')
