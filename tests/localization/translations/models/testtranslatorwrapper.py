
from tests.testcase import TestCase
from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator
from edmunds.localization.translations.models.translatorwrapper import TranslatorWrapper
from edmunds.localization.translations.exceptions.translationerror import TranslationError
import os


class TestTranslatorWrapper(TestCase):
    """
    Test the Config Translator
    """

    def set_up(self):
        """
        Set up test
        :return:    void
        """
        super(TestTranslatorWrapper, self).set_up()

        self.prefix = self.rand_str(20) + '.'
        self.prefix2 = self.rand_str(20) + '.'
        self.storage_directory = os.sep + 'storage' + os.sep
        self.logs_directory = os.sep + 'logs' + os.sep
        self.clear_paths = []
        self.config = [
            "from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator \n",
            "from edmunds.storage.drivers.file import File as StorageFile \n",
            "from edmunds.log.drivers.file import File \n",
            "from logging import WARNING \n",
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
            "   'storage': { \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'file',\n",
            "               'driver': StorageFile,\n",
            "               'directory': '%s',\n" % self.storage_directory,
            "               'prefix': '%s',\n" % self.prefix,
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "   'log': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'file1',\n",
            "               'driver': File,\n",
            "               'directory': '%s',\n" % self.logs_directory,
            "               'prefix': '%s',\n" % self.prefix2,
            "               'level': WARNING,\n"
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ]

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestTranslatorWrapper, self).tear_down()

        # Remove all profiler files
        for directory in self.clear_paths:
            for root, subdirs, files in os.walk(directory):
                for file in files:
                    if file.startswith(self.prefix):
                        os.remove(os.path.join(root, file))

    def test_get_unknown_key(self):
        """
        Test get unknown key
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write config and create app
        self.write_config(self.config)
        app = self.create_application()
        directory = app.fs().path(self.logs_directory)
        self.clear_paths.append(directory)

        fallback_locale_str = app.config('app.localization.locale.fallback')
        supported_locale_str = app.config('app.localization.locale.supported')
        data = [
            ('en', 'unknownkey1', {}),
            ('nl', 'unknownkey2', {}),
            ('nl_BE', 'unknownkey3', {}),
            ('bo', 'unknownkey4', {}),
            ('ar', 'unknownkey5', {}),
        ]

        for locale_str, key, params in data:
            with app.test_request_context(rule):
                # Fetch driver
                driver = app.localization().translator(None, given_locale_strings=[locale_str])
                self.assert_is_instance(driver, TranslatorWrapper)
                self.assert_is_instance(driver.translator, ConfigTranslator)

                with self.assert_raises_regexp(TranslationError, 'Could not find the sentence for locale "%s" and key "%s".' % (fallback_locale_str, key)):
                    driver.get(key, params)

                locale_str_error = locale_str
                if locale_str_error not in supported_locale_str:
                    locale_str_error = locale_str[:2]
                if locale_str_error not in supported_locale_str:
                    locale_str_error = fallback_locale_str

                locale_error = 'Could not find the sentence for locale "%s" and key "%s".' % (locale_str_error, key)
                self.assert_true(self._is_in_log_files(app, directory, locale_error), msg=locale_error)

    def test_get_untranslated_key(self):
        """
        Test get untranslated key
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Write config and create app
        self.write_config(self.config)
        app = self.create_application()
        directory = app.fs().path(self.logs_directory)
        self.clear_paths.append(directory)

        data = [
            ('nl', 'A smashing sentence!', 'smashing', {}),
        ]

        for locale_str, expected, key, params in data:
            with app.test_request_context(rule):
                # Fetch driver
                driver = app.localization().translator(None, given_locale_strings=[locale_str])
                self.assert_is_instance(driver, TranslatorWrapper)
                self.assert_is_instance(driver.translator, ConfigTranslator)

                self.assert_equal(expected, driver.get(key, params))
                self.assert_true(self._is_in_log_files(app, directory, 'Could not find the sentence for locale "%s" and key "%s".' % (locale_str, key)))

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

    def _is_in_log_files(self, app, directory, string, starts_with = None):
        """
        Check if string is in log files
        :param app:             The app to work with
        :type  app:             Application
        :param directory:       The directory to check
        :type  directory:       str
        :param string:          The string to check
        :type  string:          str
        :param starts_with:     The filename starts with
        :type  starts_with:     str
        :return:                Is in file
        :rtype:                 boolean
        """

        if starts_with is None:
            starts_with = self.prefix

        # Fetch files
        log_files = []
        for root, subdirs, files in os.walk(directory):
            for file in files:
                if file.startswith(starts_with):
                    log_files.append(os.path.join(self.logs_directory, file))

        # Check files
        occurs = False
        for file in log_files:
            f = app.fs().read_stream(file)

            try:
                if string in f.read():
                    occurs = True
                    break
            finally:
                f.close()

        return occurs
