from edmunds.localization.location.locationmanager import LocationManager
from edmunds.support.serviceprovider import ServiceProvider
import atexit


class LocationServiceProvider(ServiceProvider):
    """
    Location Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.location.enabled', False):
            return

        # Make manager and load instance
        manager = LocationManager(self.app)

        # Assign to extensions
        self.app.extensions['edmunds.location'] = manager

        # Close all drivers when app closes down
        def shutdown_app():
            for driver in manager.all():
                driver.close()
        atexit.register(shutdown_app)
