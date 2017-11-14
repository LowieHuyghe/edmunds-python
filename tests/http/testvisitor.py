
from tests.testcase import TestCase
from edmunds.http.visitor import Visitor
from edmunds.globals import request
from user_agents.parsers import UserAgent
from geoip2.models import City
from edmunds.localization.localization.localizator import Localizator


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

    def test_localization_not_enabled(self):
        """
        Test localization_not_enabled
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write location settings
        self.write_config([
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': False, \n",
            "   }, \n",
            "} \n",
        ])
        app = self.create_application()

        with app.test_request_context(rule):
            visitor = Visitor(app, request)
            with self.assert_raises_regexp(RuntimeError, 'Localization can not be used as it is not enabled'):
                visitor.localizator
            with self.assert_raises_regexp(RuntimeError, 'Location can not be used as localization is not enabled'):
                visitor.location

    def test_location_not_enabled(self):
        """
        Test location not enabled
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write location settings
        self.write_config([
            "from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine \n",
            "from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': True, \n",
            "       'locale': { \n",
            "           'fallback': 'en', \n",
            "           'supported': ['en'], \n",
            "       }, \n",
            "       'location': { \n",
            "           'enabled': False, \n",
            "           'instances': [ \n",
            "               { \n",
            "                   'name': 'gae',\n",
            "                   'driver': GoogleAppEngine,\n",
            "               }, \n",
            "           ], \n",
            "       }, \n",
            "       'timezonefallback': 'Europe/Brussels', \n",
            "       'translations': { \n",
            "           'enabled': True, \n",
            "           'instances': [ \n",
            "               { \n",
            "                   'name': 'configtranslator',\n",
            "                   'driver': ConfigTranslator,\n",
            "               }, \n",
            "           ], \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ])
        app = self.create_application()

        with app.test_request_context(rule):
            visitor = Visitor(app, request)
            self.assert_is_instance(visitor.localizator, Localizator)
            with self.assert_raises_regexp(RuntimeError, 'Location can not be used as it is not enabled'):
                visitor.location

    def test_localization_and_location(self):
        """
        Test location
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write location settings
        self.write_config([
            "from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine \n",
            "from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator \n",
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
            "       'timezonefallback': 'Europe/Brussels', \n",
            "       'translations': { \n",
            "           'enabled': True, \n",
            "           'instances': [ \n",
            "               { \n",
            "                   'name': 'configtranslator',\n",
            "                   'driver': ConfigTranslator,\n",
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
            self.assert_is_instance(visitor.localizator, Localizator)
            self.assert_is_instance(visitor.location, City)
