
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.auth.authmanager import AuthManager


class AuthServiceProvider(ServiceProvider):
    """
    Auth Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.auth.enabled', False):
            return

        # Make manager and load instance
        manager = AuthManager(self.app)

        # Assign to extensions
        self.app.extensions['edmunds.auth'] = manager
