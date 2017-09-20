
from edmunds.cache.cachemanager import CacheManager
from edmunds.support.serviceprovider import ServiceProvider


class CacheServiceProvider(ServiceProvider):
    """
    Cache Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.cache.enabled', False):
            return

        # Make manager and load instance
        manager = CacheManager(self.app)

        # Assign to extensions
        self.app.extensions['edmunds.cache'] = manager
