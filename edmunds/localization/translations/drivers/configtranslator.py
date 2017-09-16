
from edmunds.localization.translations.drivers.basedriver import BaseDriver
from edmunds.localization.translations.exceptions.translationerror import TranslationError
from edmunds.localization.translations.exceptions.sentencefillererror import SentenceFillerError


class ConfigTranslator(BaseDriver):
    """
    Config Translator
    """

    def get(self, localization, key, parameters=None):
        """
        Get translation
        :param localization:    Localization to use for translations
        :type localization:     edmunds.localization.localization.models.localization.Localization
        :param key:             Key of translation
        :type key:              str
        :param parameters:      Parameters used to complete the translation
        :type parameters:       dict
        :return:                The translation
        :type:                  str
        """

        config_key = 'app.localization.translations.strings.%s.%s' % (localization.locale, key)
        sentence = self.app.config(config_key)

        if sentence is None:
            raise TranslationError('Could not find the sentence for locale "%s" and key "%s".' % (localization.locale, key))

        try:
            return self.sentence_filler.fill_in(localization, sentence, params=parameters)
        except SentenceFillerError as e:
            raise SentenceFillerError('%s (locale "%s" and key "%s")' % (e, localization.locale, key))
