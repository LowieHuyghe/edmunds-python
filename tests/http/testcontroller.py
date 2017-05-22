
from tests.testcase import TestCase
from edmunds.http.controller import Controller
from edmunds.http.input import Input
from flask import request, session


class TestController(TestCase):
    """
    Test the Controller
    """

    def test_absolutely_nothing(self):
        """
        Test absolutely nothing
        :return:    void
        """

        self.assert_is_instance(MyController(self.app), MyController)

    def test_request_context(self):
        """
        Test request context
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Call route
        with self.app.test_request_context(rule):
            controller = MyController(self.app)

            self.assert_is_instance(controller, MyController)
            self.assert_equal(controller._app, self.app)
            self.assert_equal(controller._request, request)
            self.assert_is_instance(controller._input, Input)

    def test_session(self):
        """
        Test session
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
            controller = MyController(app)

            self.assert_equal_deep(session, controller._session)


class MyController(Controller):
    """
    Controller class
    """

    def initialize(self):

        return super(MyController, self).initialize()
