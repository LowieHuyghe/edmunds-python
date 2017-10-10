
from edmunds.localization.localization.models.number import Number
from edmunds.localization.localization.models.time import Time
from edmunds.localization.localization.models.localization import Localization
from edmunds.localization.translations.exceptions.translationerror import TranslationError
from edmunds.localization.translations.exceptions.sentencefillererror import SentenceFillerError
import sys


class Localizator(object):

    def __init__(self, app, most_accurate_locale, supported_locale, fallback_locale, translator, time_zone):
        """
        Constructor
        :param app:                     The app
        :type app:                      edmunds.application.Application
        :param most_accurate_locale:    The most accurate locale
        :type most_accurate_locale:     babel.core.Locale
        :param supported_locale:        The supported locale
        :type supported_locale:         babel.core.Locale
        :param fallback_locale:         The fallback locale
        :type fallback_locale:          babel.core.Locale
        :param translator:              The translator
        :type translator:               edmunds.localization.translations.drivers.basedriver.BaseDriver
        :param time_zone:               The time zone
        :type time_zone:                pytz.tzinfo.DstTzInfo
        """

        self._app = app
        self._translator = translator

        most_accurate_number = Number(most_accurate_locale)
        most_accurate_time = Time(most_accurate_locale, time_zone)
        self._most_accurate_localization = Localization(most_accurate_locale, most_accurate_number, most_accurate_time)

        supported_number = Number(supported_locale)
        supported_time = Time(supported_locale, time_zone)
        self._supported_localization = Localization(supported_locale, supported_number, supported_time)

        fallback_number = Number(fallback_locale)
        fallback_time = Time(fallback_locale, time_zone)
        self._fallback_localization = Localization(fallback_locale, fallback_number, fallback_time)

    @property
    def locale(self):
        """
        Get locale
        :return:    The locale
        :rtype:     babel.core.Locale
        """
        return self._supported_localization.locale

    @property
    def number(self):
        """
        Get number formatter
        :return:    The number formatter
        :rtype:     edmunds.localization.localization.models.number.Number
        """
        return self._supported_localization.number

    @property
    def time(self):
        """
        Get time formatter
        :return:    The time formatter
        :rtype:     edmunds.localization.localization.models.time.Time
        """
        return self._supported_localization.time

    @property
    def rtl(self):
        """
        Check if rtl
        :return:    Rtl
        :rtype:     bool
        """
        return self._supported_localization.rtl

    def translate(self, key, parameters=None):
        """
        Get translation
        :param key:         Key of translation
        :type key:          str
        :param parameters:  Parameters used to complete the translation
        :type parameters:   dict
        :return:            The translation
        :type:              str
        """

        if not self._app.config('app.localization.translations.enabled', False):
            raise RuntimeError('Translate can not be used as Translations is not enabled!')

        try:
            return self._translator.get(self._supported_localization, key, parameters=parameters)
        except TranslationError as e:
            self._app.logger.error(e, exc_info=sys.exc_info())
        except SentenceFillerError as e:
            self._app.logger.error(e, exc_info=sys.exc_info())

        return self._translator.get(self._fallback_localization, key, parameters=parameters)
