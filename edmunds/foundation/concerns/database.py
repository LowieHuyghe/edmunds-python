
from sqlalchemy.orm import scoped_session, sessionmaker
from threading import Lock


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

    def database(self, name=None, no_instance_error=False):
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
            store_key = '__default__'

        # Add key to extensions dictionary
        if store_key not in self.extensions['edmunds.database.sessions']:
            with self._database_session_lock:
                if store_key not in self.extensions['edmunds.database.sessions']:
                    # Fetch engine
                    engine = self.database(name=name, no_instance_error=no_instance_error)
                    if not engine:
                        # No engine
                        Session = None
                    else:
                        # Make factory and scoped session
                        Session = scoped_session(sessionmaker(autocommit=False, autoflush=False, bind=engine))

                    self.extensions['edmunds.database.sessions'][store_key] = Session

        return self.extensions['edmunds.database.sessions'][store_key]
