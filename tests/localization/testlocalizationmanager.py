
from tests.testcase import TestCase
from edmunds.localization.localizationmanager import LocalizationManager
from edmunds.localization.location.locationmanager import LocationManager


class TestLocalizationManager(TestCase):
    """
    Test the Localization Manager
    """

    def test_location(self):
        """
        Test location
        :return:    void
        """

        manager = self.app.localization()
        self.assert_is_instance(manager, LocalizationManager)
        # No settings for the manager
        self.assert_is_none(manager.location())

        # Write location settings
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
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)
        # with settings for the manager
        self.assert_is_not_none(manager.location())
        self.assert_is_instance(manager.location(), LocationManager)
