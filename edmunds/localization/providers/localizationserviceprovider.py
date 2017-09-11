
from edmunds.localization.location.providers.locationserviceprovider import LocationServiceProvider
from edmunds.localization.translations.providers.translationsserviceprovider import TranslationsServiceProvider
from edmunds.localization.localizationmanager import LocalizationManager
from edmunds.support.serviceprovider import ServiceProvider
from babel.core import Locale


class LocalizationServiceProvider(ServiceProvider):
    """
    Localization Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.localization.enabled', False):
            return

        # Register other providers
        self.app.register(LocationServiceProvider)
        self.app.register(TranslationsServiceProvider)

        # Make manager and load instance
        manager = LocalizationManager(self.app)

        # Assign to extensions
        self.app.extensions['edmunds.localization'] = manager

        # Validate the config
        self._validate_config(manager)

    def _validate_config(self, manager):
        """
        Validate the config
        :param manager: The localization manager
        :type manager:  edmunds.localization.localizationmanager.LocalizationManager
        :return:        void
        """

        supported_locales = manager._get_supported_locale_strings()
        fallback_locale_strings = manager._get_fallback_locale_strings()

        supported_locale = Locale.negotiate(supported_locales, fallback_locale_strings, '_')
        if not supported_locale:
            supported_string = ','.join(supported_locales)
            fallback_string = ','.join(fallback_locale_strings)
            raise RuntimeError('Could not find supported locale even with fallback! (supported: %s; fallback: %s)' % (supported_string, fallback_string))
