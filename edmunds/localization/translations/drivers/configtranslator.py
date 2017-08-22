
from edmunds.localization.translations.drivers.basedriver import BaseDriver


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
        pass
