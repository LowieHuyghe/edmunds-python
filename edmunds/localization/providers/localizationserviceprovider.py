
from edmunds.localization.location.providers.locationserviceprovider import LocationServiceProvider
from edmunds.localization.localizationmanager import LocalizationManager
from edmunds.support.serviceprovider import ServiceProvider


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
