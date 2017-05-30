
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.session.sessionmanager import SessionManager


class SessionServiceProvider(ServiceProvider):
    """
    Session Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.session.enabled', False):
            return

        # Make manager and load instance
        manager = SessionManager(self.app)

        # Assign to extensions
        self.app.extensions['edmunds.session'] = manager
