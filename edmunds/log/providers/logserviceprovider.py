
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.log.logmanager import LogManager


class LogServiceProvider(ServiceProvider):
    """
    Log Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.log.enabled', False):
            return

        # Make manager and load instance
        manager = LogManager(self.app)

        # Add each instance
        for instance in manager.all():
            self.app.logger.addHandler(instance)

        # Assign to extensions
        self.app.extensions['edmunds.log'] = manager
