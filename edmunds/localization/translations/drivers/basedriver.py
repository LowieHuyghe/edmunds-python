
from edmunds.globals import abc, ABC


class BaseDriver(ABC):
    """
    The base driver for translations-drivers
    """

    @abc.abstractmethod
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
