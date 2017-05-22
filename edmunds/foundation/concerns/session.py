
from edmunds.session.sessionmanager import SessionManager


class Session(object):
    """
    This class concerns session code for Application to extend from
    """

    def _init_session(self):
        """
        Initialise concerning session
        """

        self._session_manager = SessionManager(self)

    def session(self, name=None, no_instance_error=False):
        """
        The session to use
        :param name:                The name of the session instance
        :type  name:                str
        :param no_instance_error:   Error when no instance
        :type  no_instance_error:   bool
        :return:                    The session driver
        """

        return self._session_manager.get(name, no_instance_error=no_instance_error)
