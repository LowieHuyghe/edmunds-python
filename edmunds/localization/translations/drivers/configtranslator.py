
from edmunds.localization.translations.drivers.basedriver import BaseDriver
from edmunds.localization.translations.exceptions.translationerror import TranslationError


class ConfigTranslator(BaseDriver):
    """
    Config Translator
    """

    def get(self, locale, key, parameters=None):
        """
        Get translation
        :param locale:      Locale to use for translations
        :type locale:       babel.core.Locale
        :param key:         Key of translation
        :type key:          str
        :param parameters:  Parameters used to complete the translation
        :type parameters:   dict
        :return:            The translation
        :type:              str
        """

        config_key = 'app.localization.translations.strings.%s.%s' % (locale, key)
        sentence = self.app.config(config_key)

        if sentence is None:
            raise TranslationError('Could not find the sentence for locale "%s" and key "%s".' % (locale, key))

        return self.sentence_filler.fill_in(sentence, params=parameters)
