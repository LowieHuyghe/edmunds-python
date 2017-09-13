
from tests.testcase import TestCase
from edmunds.localization.localizationmanager import LocalizationManager
from edmunds.localization.location.drivers.basedriver import BaseDriver as LocationBaseDriver
from pytz.tzinfo import DstTzInfo
from geoip2.models import City
from edmunds.localization.localization.models.localizator import Localizator
from edmunds.localization.localization.models.number import Number
from edmunds.localization.localization.models.time import Time
from babel.core import Locale
from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator
from edmunds.localization.translations.models.translatorwrapper import TranslatorWrapper


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
            "from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': True, \n",
            "       'locale': { \n",
            "           'fallback': 'en_us', \n",
            "           'supported': ['en', 'nl_be', 'fr'], \n",
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
            "       'translations': { \n",
            "           'enabled': True, \n",
            "           'instances': [ \n",
            "               { \n",
            "                   'name': 'configtranslator',\n",
            "                   'driver': ConfigTranslator,\n",
            "               }, \n",
            "           ], \n",
            "           'strings': { \n",
            "               'en': { \n",
            "                   'beautiful': 'This is a beautiful translation. Is it not, {name}?', \n",
            "                   'smashing': 'A smashing sentence!', \n",
            "               }, \n",
            "               'nl': { \n",
            "                   'beautiful': 'Dit is een prachtige vertaling. Nietwaar, {name}?', \n",
            "               }, \n",
            "           }, \n",
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
            manager._get_time_zone(None)

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
            manager._get_time_zone(None)

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

        time_zone = manager._get_time_zone(None)
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

    def test_get_fallback_locale_strings(self):
        """
        Test _get_fallback_locale_strings
        :return:    void
        """

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        self.assert_list_equal(['en_US', 'en'], manager._get_fallback_locale_strings())

    def test_get_supported_locale_strings(self):
        """
        Test _get_supported_locale_strings
        :return:    void
        """

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        self.assert_list_equal(['en', 'nl_BE', 'fr'], manager._get_supported_locale_strings())

    def test_normalize_locale(self):
        """
        Test _normalize_locale
        :return:    void
        """

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        data = [
            ('nl_BE',   'NL_be'),
            ('nl',      'Nl'),
            ('en',      'EN_'),
            ('en',      'en_'),
            ('en',      'en__EN'),
            (None,      '*'),
            (None,      '12'),
            (None,      '&@'),
            (None,      '_en'),
            (None,      '_EN'),
            (None,      '_'),
            (None,      ''),
        ]
        for expected, given in data:
            self.assert_equal(expected, manager._normalize_locale(given))

    def test_append_backup_languages_to_locale_strings(self):
        """
        Test _append_backup_languages_to_locale_strings
        :return:    void
        """

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        data = [
            (['nl_BE', 'nl_NL', 'nl'],  ['nl_BE', 'nl_NL']),
            (['nl_BE', 'nl_NL', 'nl'],  ['nl_BE', 'nl_NL', 'nl']),
            (['nl_BE', 'nl'],           ['nl_BE']),
            (['nl'],                    ['nl']),
            ([],                        []),
        ]
        for expected, given in data:
            self.assert_list_equal(expected, manager._append_backup_languages_to_locale_strings(given))

    def test_get_browser_accept_locale_strings(self):
        """
        Test _get_browser_accept_locale_strings
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        data = [
            (['fr_CH', 'fr', 'en', 'de'], 'fr-CH, fr;q=0.9, en;q=0.8, de;q=0.7, *;q=0.5'),
            (['en_US', 'en'], 'en-US,en;q=0.5'),
            (['da', 'en_GB', 'en'], 'da, en-gb;q=0.8, en;q=0.7'),
            (['da', 'en_GB', 'en'], 'da;q=1, en-gb;q=0.8, en;q=0.7'),
            ([], '*;q=0.7'),
            ([], '*'),
            ([], ''),
        ]
        for expected, given in data:
            with app.test_request_context(rule, environ_base={'HTTP_ACCEPT_LANGUAGE': given}):
                self.assert_list_equal(expected, manager._get_browser_accept_locale_strings())

    def test_get_user_agent_locale_strings(self):
        """
        Test _get_user_agent_locale_strings
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        data = [
            (['tr_TR', 'tr'], 'Mozilla/5.0 (Linux; U; Android 2.2.2; tr-tr; GM FOX Build/HuaweiU8350) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'),
            (['nl_BE', 'nl'], 'Mozilla/5.0 (Linux; U; Android 2.3.4; nl-be; GT-S5670 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'),
            (['en_US', 'en'], 'Mozilla/5.0 (SAMSUNG; SAMSUNG-GT-S7233E/S723EJVKB1; U; Bada/1.0; en-us) AppleWebKit/533.1 (KHTML, like Gecko) Dolfin/2.0 Mobile WQVGA SMM-MMS/1.2.0 OPN-B'),
            (['de_DE', 'de'], 'Mozilla/5.0 (X11; U; Linux x86_64; de-de) AppleWebKit/537.36 (KHTML, like Gecko)  Chrome/30.0.1599.114 Safari/537.36 Puffin/3.7.0.177AP'),
            ([], 'Mozilla/5.0 (Series40; NokiaC2-02/07.63; Profile/MIDP-2.1 Configuration/CLDC-1.1) Gecko/20100401 S40OviBrowser/5.0.0.0.31'),
            ([], 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; ASU2JS; rv:11.0) like Gecko'),
        ]
        for expected, given in data:
            with app.test_request_context(rule, environ_base={'HTTP_USER_AGENT': given}):
                self.assert_list_equal(expected, manager._get_user_agent_locale_strings())

    def test_get_locale(self):
        """
        Test _get_locale
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        data = [
            (('fr', 'CH'), ('fr', None), (
                'fr-CH, fr;q=0.9, de;q=0.7, *;q=0.5',
                'Mozilla/5.0 (Linux; U; Android 2.2.2; nl-be; GM FOX Build/HuaweiU8350) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'
            )),
            (('fr', None), ('fr', None), (
                'fr;q=0.5',
                'Mozilla/5.0 (SAMSUNG; SAMSUNG-GT-S7233E/S723EJVKB1; U; Bada/1.0; nl-be) AppleWebKit/533.1 (KHTML, like Gecko) Dolfin/2.0 Mobile WQVGA SMM-MMS/1.2.0 OPN-B'
            )),
            (('nl', 'BE'), ('nl', 'BE'), (
                '*;q=0.7',
                'Mozilla/5.0 (SAMSUNG; SAMSUNG-GT-S7233E/S723EJVKB1; U; Bada/1.0; nl-be) AppleWebKit/533.1 (KHTML, like Gecko) Dolfin/2.0 Mobile WQVGA SMM-MMS/1.2.0 OPN-B'
            )),
            (('fr', None), ('fr', None), (
                'fr;q=0.5',
                'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; ASU2JS; rv:11.0) like Gecko'
            )),
            (('en', 'US'), ('en', None), (
                '*;q=0.7',
                'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; ASU2JS; rv:11.0) like Gecko'
            )),
        ]
        for (language, territory), (language_sup, territory_sup), (browser_accept_language, user_agent) in data:
            environ_base = {
                'HTTP_ACCEPT_LANGUAGE': browser_accept_language,
                'HTTP_USER_AGENT': user_agent
            }
            with app.test_request_context(rule, environ_base=environ_base):
                # Not supported
                locale = manager._get_locale(False)
                self.assert_is_instance(locale, Locale)
                self.assert_equal(language, locale.language)
                self.assert_equal(territory, locale.territory)
                # Supported
                locale = manager._get_locale(True)
                self.assert_is_instance(locale, Locale)
                self.assert_equal(language_sup, locale.language)
                self.assert_equal(territory_sup, locale.territory)

    def test_localization(self):
        """
        Localization
        :return:    None
        """

        rule = '/' + self.rand_str(20)

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        with app.test_request_context(rule):
            localizator = manager.localizator(None)
            self.assert_is_instance(localizator, Localizator)
            self.assert_is_instance(localizator.time, Time)
            self.assert_is_instance(localizator.number, Number)
            self.assert_is_instance(localizator.locale, Locale)
            self.assert_is_instance(localizator.rtl, bool)

    def test_translator_not_defined(self):
        """
        Test translator not defined
        :return:    void
        """

        # Write translator settings
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
        self.assert_is_none(manager.translator(None))

    def test_translator_disabled(self):
        """
        Test translator disabled
        :return:    void
        """

        # Write translator settings
        self.write_config([
            "from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': True, \n",
            "       'locale': { \n",
            "           'fallback': 'en', \n",
            "           'supported': ['en'], \n",
            "       }, \n",
            "       'translations': { \n",
            "           'enabled': False, \n",
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

        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        # with settings for the manager
        self.assert_is_none(manager.translator(None))

    def test_translator(self):
        """
        Translator
        :return:    None
        """

        rule = '/' + self.rand_str(20)

        # Write location settings
        self.write_config(self.valid_config)
        app = self.create_application()
        # New manager
        manager = app.localization()
        self.assert_is_instance(manager, LocalizationManager)

        with app.test_request_context(rule):
            translator = manager.translator(None)
            self.assert_is_instance(translator, TranslatorWrapper)
            self.assert_is_instance(translator.translator, ConfigTranslator)
