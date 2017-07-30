
from tests.testcase import TestCase
from edmunds.localization.localizationmanager import LocalizationManager
from edmunds.localization.location.drivers.basedriver import BaseDriver as LocationBaseDriver
from pytz.tzinfo import DstTzInfo
from geoip2.models import City


class TestLocalizationManager(TestCase):
    """
    Test the Localization Manager
    """

    def set_up(self):
        """
        Set up
        :return:    void
        """
        super(TestLocalizationManager, self).set_up()

        self.valid_config = [
            "from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': True, \n",
            "       'locale': { \n",
            "           'fallback': 'en', \n",
            "           'supported': ['en'], \n",
            "       }, \n",
            "       'time_zone_fallback': 'Europe/Brussels', \n",
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
        ]

    def test_not_defined(self):
        """
        Test not defined
        :return:    void
        """

        self.assert_is_none(self.app.localization())

    def test_not_enabled(self):
        """
        Test not defined
        :return:    void
        """

        # Write location settings
        self.write_config([
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': False, \n",
            "   }, \n",
            "} \n",
        ])
        app = self.create_application()

        # Fetch manager
        self.assert_is_none(app.localization())

    def test_location_not_defined(self):
        """
        Test location not defined
        :return:    void
        """

        # Write location settings
        self.write_config([
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': True, \n",
            "       'locale': { \n",
            "           'fallback': 'en', \n",
            "           'supported': ['en'], \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ])
        app = self.create_application()

        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        # No settings for the manager
        self.assert_is_none(manager.location())

    def test_location_disabled(self):
        """
        Test location disabled
        :return:    void
        """

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
        app = self.create_application()

        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        # with settings for the manager
        self.assert_is_none(manager.location())

    def test_location(self):
        """
        Test location
        :return:    void
        """

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        # with settings for the manager
        self.assert_is_not_none(manager.location())
        self.assert_is_instance(manager.location(), LocationBaseDriver)

    def test_time_zone_not_defined(self):
        """
        Test time zone not defined
        :return:    void
        """

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
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        with self.assert_raises_regexp(RuntimeError, 'No valid fallback time zone defined'):
            manager._get_time_zone()

    def test_time_zone_invalid(self):
        """
        Test time zone invalid
        :return:    void
        """

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
            "       'time_zone_fallback': 'Europe/Brusslesssss', \n",
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
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        with self.assert_raises_regexp(RuntimeError, 'No valid fallback time zone defined'):
            manager._get_time_zone()

    def test_time_zone_no_location(self):
        """
        Test time zone no location
        :return:    void
        """

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        time_zone = manager._get_time_zone()
        self.assert_is_not_none(time_zone)
        self.assert_is_instance(time_zone, DstTzInfo)
        self.assert_equal('Europe/Brussels', time_zone.zone)

    def test_time_zone_location_invalid(self):
        """
        Test time zone location invalid
        :return:    void
        """

        location = City({
            'location': {
                'time_zone': 'Europe/Birlennnn'
            }
        })

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        time_zone = manager._get_time_zone(location)
        self.assert_is_not_none(time_zone)
        self.assert_is_instance(time_zone, DstTzInfo)
        self.assert_equal('Europe/Brussels', time_zone.zone)

    def test_time_zone(self):
        """
        Test time zone
        :return:    void
        """

        location = City({
            'location': {
                'time_zone': 'Europe/Berlin'
            }
        })

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        time_zone = manager._get_time_zone(location)
        self.assert_is_not_none(time_zone)
        self.assert_is_instance(time_zone, DstTzInfo)
        self.assert_equal('Europe/Berlin', time_zone.zone)
