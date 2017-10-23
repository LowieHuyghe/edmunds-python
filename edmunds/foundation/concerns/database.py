
from sqlalchemy.orm import scoped_session, sessionmaker
from threading import Lock
from edmunds.globals import g


class Database(object):
    """
    This class concerns database code for Application to extend from
    """

    def _init_database(self):
        """
        Initialize database
        :return:    void
        """

        self._database_session_lock = Lock()

    def database_engine(self, name=None, no_instance_error=False):
        """
        The database to use
        :param name:                The name of the database instance
        :type  name:                str
        :param no_instance_error:   Error when no instance
        :type  no_instance_error:   bool
        :return:                    The database driver
        :rtype:                     sqlalchemy.engine.base.Engine
        """

        # Enabled?
        if not self.config('app.database.enabled', False):
            return None

        return self.extensions['edmunds.database'].get(name, no_instance_error=no_instance_error)

    def database_session(self, name=None, no_instance_error=False):
        """
        Get a session to work with
        :param name:                The name of the database instance
        :param no_instance_error:   Error when no instance
        :return:                    Session
        :rtype:                     sqlalchemy.orm.scoping.scoped_session
        """

        # Enabled?
        if not self.config('app.database.enabled', False):
            return None

        # Make extension key
        store_key = name
        if store_key is None:
            # Use default driver for name
            database_instances = self.config('app.database.instances', [])
            if database_instances:
                store_key = database_instances[0]['name']
            else:
                store_key = '__default__'

        # Make dictionary in app context
        if getattr(g, 'edmunds_database_sessions', None) is None:
            with self._database_session_lock:
                if getattr(g, 'edmunds_database_sessions', None) is None:
                    g.edmunds_database_sessions = {}

        # Add key to extensions dictionary
        if store_key not in g.edmunds_database_sessions:
            with self._database_session_lock:
                if store_key not in g.edmunds_database_sessions:
                    # Fetch engine
                    engine = self.database_engine(name=name, no_instance_error=no_instance_error)
                    if not engine:
                        # No engine
                        Session = None
                    else:
                        # Make factory and scoped session
                        Session = scoped_session(sessionmaker(autocommit=False, autoflush=False, bind=engine))

                    g.edmunds_database_sessions[store_key] = Session

        # Raise error if already requested before with no_instance_error=True
        if g.edmunds_database_sessions[store_key] is None \
                and not no_instance_error:
            raise RuntimeError('No instance declared named "%s"' % name)

        return g.edmunds_database_sessions[store_key]
