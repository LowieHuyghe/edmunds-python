
from edmunds.foundation.patterns.manager import Manager
import os
from edmunds.globals import session


class SessionManager(Manager):
    """
    Session Manager
    """

    def __init__(self, app):
        """
        Initiate the manager
        :param app:     The application
        :type  app:     Application
        """

        super(SessionManager, self).__init__(app, app.config('app.session.instances', []))

        self._session_path = os.path.join(os.sep, 'sessions')

    def _create_session_cookie(self, config):
        """
        Create SessionCookie instance
        :param config:  The config
        :type  config:  dict
        :return:        SessionCookie instance
        :rtype:         SessionCookie
        """

        return session
