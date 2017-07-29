
from tests.testcase import TestCase
from edmunds.localization.localizationmanager import LocalizationManager
from edmunds.localization.location.locationmanager import LocationManager


class TestLocationServiceProvider(TestCase):
    """
    Test the Location Service Provider
    """

    def test_provider_not_defined(self):
        """
        Test provider not defined
        :return:    void
        """

        # Should also have registered location-manager
        self.assert_not_in('edmunds.localization.location', self.app.extensions)

    def test_provider_disabled(self):
        """
        Test the provider disabled
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'location': { \n",
            "           'enabled': False, \n",
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

        # Should not have registered location-manager
        self.assert_not_in('edmunds.localization.location', app.extensions)

    def test_provider(self):
        """
        Test the provider
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

        # Should also have registered location-manager
        self.assert_in('edmunds.localization.location', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.localization.location'])
        self.assert_is_instance(app.extensions['edmunds.localization.location'], LocationManager)
