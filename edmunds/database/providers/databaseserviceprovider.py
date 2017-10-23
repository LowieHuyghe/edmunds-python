
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.database.databasemanager import DatabaseManager
from edmunds.globals import g


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

        # Make place to store sessions
        self.app.extensions['edmunds.database.sessions'] = {}

        # Tear down db sessions when request ends or app shuts down
        @self.app.teardown_appcontext
        def shutdown_session(exception=None):
            # Remove all sessions in app context
            if getattr(g, 'edmunds_database_sessions', None) is not None:
                for key in g.edmunds_database_sessions:
                    session = g.edmunds_database_sessions[key]
                    if session is not None:
                        session.remove()
