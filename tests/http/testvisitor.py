
from tests.testcase import TestCase
from edmunds.http.visitor import Visitor
from edmunds.globals import request
from user_agents.parsers import UserAgent
from geoip2.models import City


class TestVisitor(TestCase):
    """
    Test Visitor
    """

    def test_client(self):
        """
        Test client
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Call route
        with self.app.test_request_context(rule):
            visitor = Visitor(self.app, request)
            self.assert_is_instance(visitor.client, UserAgent)

    def test_location(self):
        """
        Test location
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write location settings
        self.write_config([
            "from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': True, \n",
            "       'locale': { \n",
            "           'fallback': 'en', \n",
            "           'supported': ['en'], \n",
            "       }, \n",
            "       'location': { \n",
            "           'enabled': True, \n",
            "           'instances': [ \n",
            "               { \n",
            "                   'name': 'gae',\n",
            "                   'driver': GoogleAppEngine,\n",
            "               }, \n",
            "           ], \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ])
        app = self.create_application()

        # Call route
        with app.test_request_context(rule):
            visitor = Visitor(app, request)
            self.assert_is_instance(visitor.location, City)
