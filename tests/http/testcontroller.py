
from tests.testcase import TestCase
from edmunds.http.controller import Controller
from edmunds.http.input import Input
from edmunds.globals import request, session, visitor
from edmunds.http.responsehelper import ResponseHelper


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
            self.app.preprocess_request()

            controller = MyController(self.app)

            self.assert_is_instance(controller, MyController)
            self.assert_equal(controller.app, self.app)
            self.assert_equal(controller.request, request)
            self.assert_equal(controller.visitor, visitor)

    def test_input(self):
        """
        Test input
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Call route
        with self.app.test_request_context(rule):
            controller = MyController(self.app)

            self.assert_is_instance(controller.input, Input)
            controller.input = rule
            self.assert_equal_deep(rule, controller.input)

    def test_response(self):
        """
        Test response
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Call route
        with self.app.test_request_context(rule):
            controller = MyController(self.app)

            self.assert_is_instance(controller.response, ResponseHelper)
            controller.response = rule
            self.assert_equal_deep(rule, controller.response)

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
            controller = MyController(app)

            self.assert_equal_deep(session, controller.session)
            controller.session = rule
            self.assert_equal_deep(rule, controller.session)


class MyController(Controller):
    """
    Controller class
    """

    def initialize(self):

        return super(MyController, self).initialize()
