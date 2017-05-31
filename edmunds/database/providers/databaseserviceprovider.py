
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.database.databasemanager import DatabaseManager


class DatabaseServiceProvider(ServiceProvider):
    """
    Database Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.database.enabled', False):
            return

        # Make manager and load instance
        manager = DatabaseManager(self.app)

        # Assign to extensions
        self.app.extensions['edmunds.database'] = manager
