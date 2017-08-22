from edmunds.localization.translations.translationsmanager import TranslationsManager
from edmunds.support.serviceprovider import ServiceProvider


class TranslationsServiceProvider(ServiceProvider):
    """
    Translations Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.localization.translations.enabled', False):
            return

        # Make manager and load instance
        manager = TranslationsManager(self.app)

        # Assign to extensions
        self.app.extensions['edmunds.localization.translations'] = manager
