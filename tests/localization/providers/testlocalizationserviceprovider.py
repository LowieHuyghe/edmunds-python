
from tests.testcase import TestCase
from edmunds.localization.localizationmanager import LocalizationManager
from edmunds.localization.location.locationmanager import LocationManager


class TestLocalizationServiceProvider(TestCase):
    """
    Test the Localization Service Provider
    """

    def test_provider(self):
        """
        Test provider
        :return:    void
        """

        # Test extension
        self.assert_in('edmunds.localization', self.app.extensions)
        self.assert_is_not_none(self.app.extensions['edmunds.localization'])
        self.assert_is_instance(self.app.extensions['edmunds.localization'], LocalizationManager)

        # Should also have registered location-manager
        self.assert_not_in('edmunds.localization.location', self.app.extensions)

    def test_provider_with_submanagers(self):
        """
        Test the provider with submanagers
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine \n",
            "APP = { \n",
            "   'localization': { \n",
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

        # Test extension
        self.assert_in('edmunds.localization', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.localization'])
        self.assert_is_instance(app.extensions['edmunds.localization'], LocalizationManager)

        # Should also have registered location-manager
        self.assert_in('edmunds.localization.location', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.localization.location'])
        self.assert_is_instance(app.extensions['edmunds.localization.location'], LocationManager)
