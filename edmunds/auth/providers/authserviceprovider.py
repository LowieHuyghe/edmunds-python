
from edmunds.support.serviceprovider import ServiceProvider
from flask_security import Security, SQLAlchemyUserDatastore
from edmunds.database.table import db


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

        # Database enabled?
        if not self.app.config('app.database.enabled', False):
            raise RuntimeError("Auth requires database to be enabled.")

        # User and Role model
        user_class = self.app.config('app.auth.models.user', False)
        role_class = self.app.config('app.auth.models.role', False)
        if user_class is None \
                or role_class is None:
            raise RuntimeError("Auth-config is missing some configuration ('app.auth.models.user' and 'app.auth.models.role' are required).")

        # Setup Flask-Security
        userdatastore = SQLAlchemyUserDatastore(db, user_class, role_class)
        security = Security(self.app, userdatastore)

        self.app.extensions['edmunds.auth.userdatastore'] = userdatastore
        self.app.extensions['edmunds.auth.security'] = security
