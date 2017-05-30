
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.storage.storagemanager import StorageManager


class StorageServiceProvider(ServiceProvider):
    """
    Storage Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Make manager and load instance
        manager = StorageManager(self.app, self.app.root_path, 'storage')

        # Assign to extensions
        self.app.extensions['edmunds.storage'] = manager
