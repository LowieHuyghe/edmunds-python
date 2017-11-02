
from edmunds.foundation.patterns.manager import Manager
from flask_security import Security, SQLAlchemyUserDatastore
from edmunds.database.db import db


class AuthManager(Manager):
    """
    Auth Manager
    """

    def __init__(self, app):
        """
        Initiate the manager
        :param app:             The application
        :type  app:             Application
        """

        super(AuthManager, self).__init__(app, app.config('app.auth.instances', []))

    def _create_sql_alchemy_user_datastore(self, config):
        """
        Create my SQLAlchemyUserDatastore Security instance
        :param config:  The config
        :return:        Security instance
        :rtype:         flask_security.Security
        """

        # Database enabled?
        if not self._app.config('app.database.enabled', False):
            raise RuntimeError("Auth requires database to be enabled.")

        # Check config
        if 'models' not in config \
                or 'user' not in config['models'] \
                or 'role' not in config['models'] \
                or config['models']['user'] is None \
                or config['models']['role'] is None:
            raise RuntimeError("Auth-driver '%s' is missing some configuration ('models.user' and 'models.role' are required)." % config['name'])

        # User and Role model
        user_class = config['models']['user']
        role_class = config['models']['role']

        # Setup user data store
        userdatastore = SQLAlchemyUserDatastore(db, user_class, role_class)

        # Setup Security
        return Security(self._app, userdatastore)
