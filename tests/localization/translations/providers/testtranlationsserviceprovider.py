
from tests.testcase import TestCase
from edmunds.localization.translations.translationsmanager import TranslationsManager


class TestTranslationsServiceProvider(TestCase):
    """
    Test the Translations Service Provider
    """

    def test_provider_not_defined(self):
        """
        Test provider not defined
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator \n",
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
        # Create app
        app = self.create_application()

        self.assert_not_in('edmunds.localization.translations', app.extensions)

    def test_provider_disabled(self):
        """
        Test the provider disabled
        :return:    void
        """

        # Write config
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

        # Create app
        app = self.create_application()

        # Should not have registered translations-manager
        self.assert_not_in('edmunds.localization.translations', app.extensions)

    def test_provider(self):
        """
        Test the provider
        :return:    void
        """

        # Write config
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

        # Create app
        app = self.create_application()

        # Should also have registered translations-manager
        self.assert_in('edmunds.localization.translations', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.localization.translations'])
        self.assert_is_instance(app.extensions['edmunds.localization.translations'], TranslationsManager)
