
from edmunds.localization.translations.exceptions.translationerror import TranslationError
from edmunds.localization.translations.exceptions.sentencefillererror import SentenceFillerError


class TranslatorWrapper(object):

    def __init__(self, app, translator, localizator, localizator_fallback):
        """
        Constructor
        :param app:                     The application
        :type app:                      edmunds.application.Application
        :param translator:              The translator-driver
        :type translator:               edmunds.localization.translations.drivers.basedriver.BaseDriver
        :param localizator:            The localizator
        :type localizator:             edmunds.localization.localization.models.localizator.Localizator
        :param localizator_fallback:   The fallback localizator
        :type localizator_fallback:    edmunds.localization.localization.models.localizator.Localizator
        """

        self.app = app
        self.translator = translator
        self.localizator = localizator
        self.localizator_fallback = localizator_fallback

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
            return self.translator.get(self.localizator, key, parameters=parameters)
        except TranslationError as e:
            self.app.logger.error(e)
        except SentenceFillerError as e:
            self.app.logger.error(e)

        return self.translator.get(self.localizator_fallback, key, parameters=parameters)
