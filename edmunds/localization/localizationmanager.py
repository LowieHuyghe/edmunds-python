
from edmunds.localization.localization.localizator import Localizator
from babel.core import Locale
from babel.dates import get_timezone
from edmunds.globals import request
import re


class LocalizationManager(object):

    def __init__(self, app):
        """
        Constructor
        :param app: The app 
        """
        self._app = app

    def location(self, name=None, no_instance_error=False):
        """
        The location driver
        :param name:                The name of the session instance
        :type  name:                str
        :param no_instance_error:   Error when no instance
        :type  no_instance_error:   bool
        :return:                    A location driver
        :rtype:                     edmunds.localization.location.drivers.basedriver.BaseDriver
        """

        # Enabled?
        if not self._app.config('app.localization.location.enabled', False):
            return None

        # Return driver
        return self._app.extensions['edmunds.localization.location'].get(name, no_instance_error=no_instance_error)

    def translator(self, name=None, no_instance_error=False):
        """
        Return translation driver
        :param name:                    The name of the session instance
        :type  name:                    str
        :param no_instance_error:       Error when no instance
        :type  no_instance_error:       bool
        :return:                        Translation driver
        :rtype:                         edmunds.localization.translations.drivers.basedriver.BaseDriver
        """

        # Enabled?
        if not self._app.config('app.localization.translations.enabled', False):
            return None

        return self._app.extensions['edmunds.localization.translations'].get(name, no_instance_error=no_instance_error)

    def localizator(self, location, translator, given_locale_strings=None, translator_name=None, translator_no_instance_error=False):
        """
        Return localizator
        :param location:                        The location
        :type location:                         geoip2.models.City
        :param translator:                      The translation driver
        :type translator:                       edmunds.localization.translations.drivers.basedriver.BaseDriver
        :param given_locale_strings:            List of given locale strings to determine locale
        :type given_locale_strings:             list
        :param translator_name:                 The translator_name of the session instance
        :type  translator_name:                 str
        :param translator_no_instance_error:    Error when no instance
        :type  translator_no_instance_error:    bool
        :return:                                Localizator instance
        :rtype:                                 edmunds.localization.localization.localizator.Localizator
        """

        most_accurate_locale = self._get_locale(False, given_locale_strings=given_locale_strings)
        supported_locale = self._get_locale(True, given_locale_strings=given_locale_strings)
        fallback_locale = self._get_locale(False, only_fallback_locales=True)

        time_zone = self._get_time_zone(location)

        return Localizator(self._app, most_accurate_locale, supported_locale, fallback_locale, translator, time_zone)

    def _get_time_zone(self, location):
        """
        Get timezone
        :param location:    The location
        :type location:     geoip2.models.City
        :return:            The time zone
        :rtype:             pytz.tzinfo.DstTzInfo
        """

        if location:
            time_zone_string = location.location.time_zone
            try:
                return get_timezone(time_zone_string)
            except LookupError:
                pass

        time_zone_string = self._app.config('app.localization.time_zone_fallback', None)
        if time_zone_string:
            try:
                return get_timezone(time_zone_string)
            except LookupError:
                pass

        raise RuntimeError("No valid fallback time zone defined! ('app.localization.time_zone_fallback')")

    def _get_locale(self, from_supported_locales, given_locale_strings=None, only_fallback_locales=False):
        """
        Get locale
        :param from_supported_locales:  Only return locale that is supported according to config
        :type from_supported_locales:   bool
        :param given_locale_strings:    List of given locale strings to determine locale
        :type given_locale_strings:     list
        :param only_fallback_locales:   Only use fallback locales, No browser accept ot user agent locales.
        :type only_fallback_locales:    bool
        :return:                        Locale
        :rtype:                         babel.core.Locale
        """

        # List with all client locales
        given_locale_strings = self._get_processed_given_locale_strings(given_locale_strings)
        if not only_fallback_locales:
            browser_accept_locale_strings = self._get_browser_accept_locale_strings()
            user_agent_locale_strings = self._get_user_agent_locale_strings()
        else:
            browser_accept_locale_strings = []
            user_agent_locale_strings = []
        fallback_locale_strings = self._get_fallback_locale_strings()
        preferred_locale_strings = given_locale_strings + browser_accept_locale_strings + user_agent_locale_strings + fallback_locale_strings

        # Only supported
        if not preferred_locale_strings:
            raise RuntimeError('No preferred locales to use, even with fallback!')
        elif from_supported_locales:
            supported_locales = self._get_supported_locale_strings()
            wanted_locale = Locale.negotiate(preferred_locale_strings, supported_locales, sep='_')
            if not wanted_locale:
                raise RuntimeError('Could not find supported locale even with fallback! (%s; %s; %s; %s)' % (','.join(given_locale_strings), ','.join(browser_accept_locale_strings), ','.join(user_agent_locale_strings), ','.join(fallback_locale_strings)))
        else:
            wanted_locale = preferred_locale_strings[0]

        # Process
        return Locale.parse(wanted_locale, sep='_')

    def _get_processed_given_locale_strings(self, given_locale_strings):
        """
        Get processed given locale strings
        :param given_locale_strings:    List of given locale strings to determine locale
        :type given_locale_strings:     list
        :return:    list
        """
        if not given_locale_strings:
            return []

        given_locale_strings = list(map(self._normalize_locale, given_locale_strings))
        given_locale_strings = list(filter(lambda x: x, given_locale_strings))

        # Append backup languages
        given_locale_strings = self._append_backup_languages_to_locale_strings(given_locale_strings)

        return given_locale_strings

    def _get_browser_accept_locale_strings(self):
        """
        Get browser accept locale strings
        :return:    list
        """
        # Accept Language
        browser_locales = request.accept_languages.values()
        browser_locales = list(map(self._normalize_locale, browser_locales))
        browser_locales = list(filter(lambda x: x, browser_locales))

        # Append backup languages
        browser_locales = self._append_backup_languages_to_locale_strings(browser_locales)

        return browser_locales

    def _get_user_agent_locale_strings(self):
        """
        Get user agent locale strings
        :return:    list
        """
        # User Agent
        user_agent_locale = request.user_agent.language
        user_agent_locale = self._normalize_locale(user_agent_locale)

        # Return list of locale strings
        if not user_agent_locale:
            return []

        # Append backup languages
        user_agent_locales = [user_agent_locale]
        user_agent_locales = self._append_backup_languages_to_locale_strings(user_agent_locales)

        return user_agent_locales

    def _get_fallback_locale_strings(self):
        """
        Get fallback locale strings
        :return:    list
        """
        # Config Fallback
        config_fallback_locale = self._app.config('app.localization.locale.fallback', None)
        config_fallback_locale = self._normalize_locale(config_fallback_locale)

        # Throw error if no fallback
        if not config_fallback_locale:
            raise RuntimeError("No valid fallback locale defined! ('app.localization.locale.fallback')")

        # Append backup languages
        config_fallback_locales = [config_fallback_locale]
        config_fallback_locales = self._append_backup_languages_to_locale_strings(config_fallback_locales)

        return config_fallback_locales

    def _get_supported_locale_strings(self):
        """
        Get supported locale string
        :return:    list
        """
        supported_locales = self._app.config('app.localization.locale.supported', [])
        supported_locales = list(map(self._normalize_locale, supported_locales))
        supported_locales = list(filter(lambda x: x, supported_locales))

        if not supported_locales:
            raise RuntimeError("No valid supported locales defined! ('app.localization.locale.supported')")

        return supported_locales

    def _normalize_locale(self, locale_string):
        """
        Normalize locale
        :param locale_string:   Locale string
        :return:                Normalized locale string
        """
        if not locale_string:
            return None

        # Check if valid
        if not re.match(r'^[a-zA-Z_\-]+$', locale_string):
            return None

        # Change separator and split
        locale_string = locale_string.replace('-', '_')
        locale_string_parts = locale_string.split('_')

        # Validate first part
        if not locale_string_parts[0]:
            return None
        # Lower case first part
        processed_locale_string_parts = [locale_string_parts[0].lower()]
        # Upper case other parts as long as parts are valid
        for locale_string_part in locale_string_parts[1:]:
            if not locale_string_part:
                break
            processed_locale_string_parts.append(locale_string_part.upper())

        # Join
        locale_string = '_'.join(processed_locale_string_parts)

        # Return
        return locale_string

    def _append_backup_languages_to_locale_strings(self, locale_strings):
        """
        Append languages to locale strings
        :param locale_strings:  List of locales (ex: [de_DE, de_BE fr_FR])
        :return:                List with appended languages (ex: [de_DE, de_BE, de, fr_FR, fr]
        """

        complete_locale_strings = []

        last_language = None

        for locale_string in locale_strings:
            complete_locale_strings.append(locale_string)

            if '_' not in locale_string:
                last_language = None
            else:
                locale_language = locale_string.split('_')[0]
                if last_language != locale_language:
                    if last_language:
                        complete_locale_strings.append(last_language)
                    last_language = locale_language
        if last_language:
            complete_locale_strings.append(last_language)

        return complete_locale_strings
