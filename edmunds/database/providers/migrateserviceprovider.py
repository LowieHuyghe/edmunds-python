
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.database.databasemanager import DatabaseManager
from flask_migrate import Migrate
from pkgutil import walk_packages
import os.path


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

        # Load all models
        model_paths = self.app.config('app.database.models', [
            os.path.join('app', 'models')
        ])
        # Join with root_path
        model_paths = list(map(lambda path: os.path.join(self.app.root_path, path), model_paths))
        # Load all models
        for loader, name, is_pkg in walk_packages(path=model_paths):
            loader.find_module(name).load_module(name)
