
from tests.testcase import TestCase
import os
from flask import session
from flask.sessions import SecureCookieSession
from werkzeug.local import LocalProxy


class TestSessionCookie(TestCase):
    """
    Test the SessionCookie
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestSessionCookie, self).set_up()

        self.prefix = self.rand_str(20) + '.'
        self.storage_directory = os.sep + 'storage' + os.sep
        self.clear_paths = []

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestSessionCookie, self).tear_down()

        # Remove all profiler files
        for directory in self.clear_paths:
            for root, subdirs, files in os.walk(directory):
                for file in files:
                    if file.startswith(self.prefix):
                        os.remove(os.path.join(root, file))

    def test_session_cookie(self):
        """
        Test the file
        """

        rule = '/' + self.rand_str(20)
        key = self.rand_str(20)
        value = self.rand_str(20)
        secret_key = self.rand_str(24)

        # Write config
        self.write_config([
            "from edmunds.session.drivers.sessioncookie import SessionCookie \n",
            "SECRET_KEY = '%s'\n" % secret_key,
            "APP = { \n",
            "   'session': { \n",
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

            driver = app.session()
            self.assert_equal_deep(session, driver)
            self.assert_is_instance(driver, LocalProxy)
            self.assert_is_instance(driver._get_current_object(), SecureCookieSession)

            self.assert_false(driver.modified)
            self.assert_not_in(key, driver)

            driver[key] = value

            self.assert_true(driver.modified)
            self.assert_in(key, driver)

            driver.pop(key, None)

            self.assert_true(driver.modified)
            self.assert_not_in(key, driver)
