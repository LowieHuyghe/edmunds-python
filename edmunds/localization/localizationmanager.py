
from edmunds.localization.localization.models.localization import Localization
from edmunds.localization.localization.models.number import Number
from edmunds.localization.localization.models.time import Time
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

    def localization(self, location=None):
        """
        Return localization
        :param location:    The location
        :type location:     geoip2.models.City
        :return: edmunds.localization.localization.models.localization.Localization
        """
        locale = self._get_locale(False)
        time_zone = self._get_time_zone(location)
        number = Number(locale)
        time = Time(locale, time_zone)

        return Localization(locale, number, time)

    def _get_time_zone(self, location=None):
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

    def _get_locale(self, from_supported_locales):
        """
        Get locale
        :param from_supported_locales:  Only return locale that is supported according to config
        :type from_supported_locales:   bool
        :return:                        Locale
        :rtype:                         babel.core.Locale
        """

        # List with all client locales
        browser_accept_locale_strings = self._get_browser_accept_locale_strings()
        user_agent_locale_strings = self._get_user_agent_locale_strings()
        fallback_locale_strings = self._get_fallback_locale_strings()
        preferred_locale_strings = browser_accept_locale_strings + user_agent_locale_strings + fallback_locale_strings

        # Only supported
        if not preferred_locale_strings:
            raise RuntimeError('No preferred locales to use, even with fallback!')
        elif from_supported_locales:
            supported_locales = self._get_supported_locale_strings()
            wanted_locale = Locale.negotiate(preferred_locale_strings, supported_locales, sep='_')
            if not wanted_locale:
                raise RuntimeError('Could not find supported locale even with fallback! (%s; %s; %s)' % (','.join(browser_accept_locale_strings), ','.join(user_agent_locale_strings), ','.join(fallback_locale_strings)))
        else:
            wanted_locale = preferred_locale_strings[0]

        # Process
        return Locale.parse(wanted_locale, sep='_')

    def _get_browser_accept_locale_strings(self):
        """
        Get browser accept locale strings
        :return:    list
        """
        # Accept Language
        browser_locales = request.accept_languages.values()
        browser_locales = list(map(self._normalize_locale, browser_locales))
        browser_locales = list(filter(lambda x: x, browser_locales))

        # Add to list
        preferred_locale_strings = browser_locales[:]
        preferred_locale_strings = self._append_backup_languages_to_locale_strings(preferred_locale_strings)

        return preferred_locale_strings

    def _get_user_agent_locale_strings(self):
        """
        Get user agent locale strings
        :return:    list
        """
        # Make list
        preferred_locale_strings = []

        # User Agent
        user_agent_locale = request.user_agent.language
        user_agent_locale = self._normalize_locale(user_agent_locale)

        # Add to list
        if user_agent_locale:
            preferred_locale_strings.append(user_agent_locale)
            preferred_locale_strings = self._append_backup_languages_to_locale_strings(preferred_locale_strings)

        return preferred_locale_strings

    def _get_fallback_locale_strings(self):
        """
        Get fallback locale strings
        :return:    list
        """

        # Make list
        preferred_locale_strings = []

        # Config Fallback
        config_fallback_locale = self._app.config('app.localization.locale.fallback', None)
        config_fallback_locale = self._normalize_locale(config_fallback_locale)
        # Add to list
        if config_fallback_locale:
            preferred_locale_strings.append(config_fallback_locale)
            preferred_locale_strings = self._append_backup_languages_to_locale_strings(preferred_locale_strings)
        else:
            raise RuntimeError("No valid fallback locale defined! ('app.localization.locale.fallback')")

        return preferred_locale_strings

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
