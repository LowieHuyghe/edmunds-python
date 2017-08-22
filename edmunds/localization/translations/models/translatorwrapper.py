
class TranslatorWrapper(object):

    def __init__(self, translator, locale):
        """
        Constructor
        :param translator:  The translator-driver
        :type translator:   edmunds.localization.translations.drivers.basedriver.BaseDriver
        :param locale:      The locale
        :type locale:       babel.core.Locale
        """

        self.translator = translator
        self.locale = locale

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

        return self.translator.get(self.locale, key, parameters=parameters)
