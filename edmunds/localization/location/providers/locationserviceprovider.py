from edmunds.localization.location.locationmanager import LocationManager
from edmunds.support.serviceprovider import ServiceProvider


class LocationServiceProvider(ServiceProvider):
    """
    Location Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.localization.location.enabled', False):
            return

        # Make manager and load instance
        manager = LocationManager(self.app)

        # Assign to extensions
        self.app.extensions['edmunds.localization.location'] = manager
