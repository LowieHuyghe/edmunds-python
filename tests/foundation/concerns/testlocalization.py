
from tests.testcase import TestCase
from edmunds.localization.localizationmanager import LocalizationManager
from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine


class TestLocalization(TestCase):
    """
    Test the Localization
    """

    def test_not_enabled(self):
        """
        Test not enabled
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': False, \n",
            "       'locale': { \n",
            "           'fallback': 'en', \n",
            "           'supported': ['en'], \n",
            "       }, \n",
            "   }, \n",
            "} \n",
            ])

        # Create app
        app = self.create_application()

        self.assert_is_none(app.localization())

    def test_enabled(self):
        """
        Test enabled
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write config
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

        # Create app
        app = self.create_application()

        # Test session
        with app.test_request_context(rule):
            self.assert_is_not_none(app.localization())
            self.assert_is_instance(app.localization(), LocalizationManager)

            self.assert_is_not_none(app.localization().location())
            self.assert_is_instance(app.localization().location(), GoogleAppEngine)
            self.assert_is_not_none(app.localization().location('gae'))
            self.assert_is_instance(app.localization().location('gae'), GoogleAppEngine)

            with self.assert_raises_regexp(RuntimeError, 'No instance declared'):
                app.localization().location('gae2')
            self.assert_is_none(app.localization().location('gae2', no_instance_error=True))
