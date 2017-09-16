
from edmunds.globals import abc, ABC


class BaseDriver(ABC):
    """
    The base driver for translations-drivers
    """

    def __init__(self, app, sentence_filler):
        """
        Constructor
        :param app:             The application
        :type app:              edmunds.application.Application
        :param sentence_filler: The sentence filler
        :type sentence_filler:  edmunds.localization.translations.sentencefiller.SentenceFiller
        """
        super(BaseDriver, self).__init__()

        self.app = app
        self.sentence_filler = sentence_filler

    @abc.abstractmethod
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
        pass
