
from tests.testcase import TestCase
from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator
from edmunds.localization.translations.models.translatorwrapper import TranslatorWrapper
from edmunds.localization.translations.exceptions.translationerror import TranslationError
from edmunds.localization.translations.exceptions.sentencefillererror import SentenceFillerError


class TestConfigTranslator(TestCase):
    """
    Test the Config Translator
    """

    def set_up(self):
        """
        Set up test
        :return:    void
        """
        super(TestConfigTranslator, self).set_up()

        self.config = [
            "from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'enabled': True, \n",
            "       'locale': { \n",
            "           'fallback': 'en', \n",
            "           'supported': ['en', 'nl'], \n",
            "       }, \n",
            "       'time_zone_fallback': 'Europe/Brussels', \n",
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

    def test_get_unknown_key(self):
        """
        Test get unknown key
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write config and create app
        self.write_config(self.config)
        app = self.create_application()
        fallback_locale_str = app.config('app.localization.locale.fallback')

        data = [
            ('en', 'beautiful', {}),
            ('nl', 'beautiful', {}),
            ('nl_BE', 'beautiful', {}),
        ]

        for locale_str, key, params in data:
            with app.test_request_context(rule):
                # Fetch driver
                driver = app.localization().translator(None, given_locale_strings=[locale_str])
                self.assert_is_instance(driver, TranslatorWrapper)
                self.assert_is_instance(driver.translator, ConfigTranslator)

                with self.assert_raises_regexp(SentenceFillerError, 'Param "name" could not be replaced. \(locale "%s" and key "%s"\)' % (fallback_locale_str, key)):
                    driver.get(key, params)

    def test_get_errors(self):
        """
        Test get errors
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write config and create app
        self.write_config(self.config)
        app = self.create_application()

        fallback_locale_str = app.config('app.localization.locale.fallback')
        data = [
            ('en', 'unknownkey1', {}),
            ('nl', 'unknownkey2', {}),
            ('nl_BE', 'unknownkey3', {}),
            ('bo', 'unknownkey1', {}),
            ('ar', 'unknownkey2', {}),
        ]

        for locale_str, key, params in data:
            with app.test_request_context(rule):
                # Fetch driver
                driver = app.localization().translator(None, given_locale_strings=[locale_str])
                self.assert_is_instance(driver, TranslatorWrapper)
                self.assert_is_instance(driver.translator, ConfigTranslator)

                with self.assert_raises_regexp(TranslationError, 'Could not find the sentence for locale "%s" and key "%s".' % (fallback_locale_str, key)):
                    driver.get(key, params)

    def test_get(self):
        """
        Test get
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write config and create app
        self.write_config(self.config)
        app = self.create_application()

        data = [
            ('en', 'A smashing sentence!', 'smashing', {}),
            ('nl', 'A smashing sentence!', 'smashing', {}),
            ('nl_BE', 'A smashing sentence!', 'smashing', {}),
            ('en', 'This is a beautiful translation. Is it not, Steve?', 'beautiful', {'name': 'Steve'}),
            ('nl', 'Dit is een prachtige vertaling. Nietwaar, Steve?', 'beautiful', {'name': 'Steve'}),
            ('nl_BE', 'Dit is een prachtige vertaling. Nietwaar, Steve?', 'beautiful', {'name': 'Steve'}),
        ]

        for locale_str, expected, key, params in data:
            with app.test_request_context(rule):
                # Fetch driver
                driver = app.localization().translator(None, given_locale_strings=[locale_str])
                self.assert_is_instance(driver, TranslatorWrapper)
                self.assert_is_instance(driver.translator, ConfigTranslator)

                self.assert_equal(expected, driver.get(key, params))
