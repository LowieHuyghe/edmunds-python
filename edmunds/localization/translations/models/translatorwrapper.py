
from edmunds.localization.translations.exceptions.translationerror import TranslationError
from edmunds.localization.translations.exceptions.sentencefillererror import SentenceFillerError


class TranslatorWrapper(object):

    def __init__(self, app, translator, locale, locale_fallback):
        """
        Constructor
        :param app:             The application
        :type app:              edmunds.application.Application
        :param translator:      The translator-driver
        :type translator:       edmunds.localization.translations.drivers.basedriver.BaseDriver
        :param locale:          The locale
        :type locale:           babel.core.Locale
        :param locale_fallback: The fallback locale
        :type locale_fallback:  babel.core.Locale
        """

        self.app = app
        self.translator = translator
        self.locale = locale
        self.locale_fallback = locale_fallback

    def get(self, key, parameters=None):
        """
        Get translation
        :param key:         Key of translation
        :type key:          str
        :param parameters:  Parameters used to complete the translation
        :type parameters:   dict
        :return:            The translation
        :type:              str
        """

        try:
            return self.translator.get(self.locale, key, parameters=parameters)
        except TranslationError as e:
            self.app.logger.error(e)
        except SentenceFillerError as e:
            self.app.logger.error(e)

        return self.translator.get(self.locale_fallback, key, parameters=parameters)
