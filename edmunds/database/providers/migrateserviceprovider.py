
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.database.databasemanager import DatabaseManager
from flask_migrate import Migrate


class MigrateServiceProvider(ServiceProvider):
    """
    Migrate Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        # Enabled?
        if not self.app.config('app.database.enabled', False):
            return

        # Load instances (which binds sql-alchemy)
        self.app.extensions['edmunds.database'].get(no_instance_error=True)

        # Assign to extensions
        migrate = Migrate(self.app, DatabaseManager.get_sql_alchemy_instance())
        self.app.extensions['edmunds.database.manager'] = migrate
