
from edmunds.localization.location.providers.locationserviceprovider import LocationServiceProvider
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

        # Register other providers
        self.app.register(LocationServiceProvider)

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

        supported_locales = self._app.config('app.localization.locale.supported', [])
        if not supported_locales:
            raise RuntimeError("There are no supported locales defined in 'app.localization.locale.supported'!")

        fallback_locale_strings = manager._get_fallback_locale_strings()
        if not fallback_locale_strings:
            raise RuntimeError("There are no fallback locales defined!")

        supported_locale = Locale.negotiate(supported_locales, fallback_locale_strings, '_')
        if not supported_locale:
            raise RuntimeError('Could not find supported locale even with fallback! (supported: %s; fallback: %s)' % (','.join(supported_locales), ','.join(fallback_locale_strings)))
