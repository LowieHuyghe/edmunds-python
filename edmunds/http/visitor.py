
from threading import Lock
from user_agents import parse


class Visitor(object):

    def __init__(self, app, request):
        """
        Constructor
        :param app:     The app
        :type app:      edmunds.application.Application
        :param request: The request
        :type request:  edmunds.http.request.Request
        """

        self._app = app
        self._request = request
        self.__client = None
        self.__client_lock = Lock()
        self.__location = None
        self.__location_lock = Lock()
        self.__localization = None
        self.__localization_lock = Lock()

    @property
    def client(self):
        """
        Get client information
        :return:    Client info
        :rtype:     user_agents.parsers.UserAgent
        """

        if self.__client is None:
            with self.__client_lock:
                if self.__client is None:
                    self.__client = parse(self._request.user_agent.string)
        return self.__client

    @property
    def location(self):
        """
        Get location information
        :return:    Location info
        :rtype:     geoip2.models.City
        """

        if self.__location is None:
            with self.__location_lock:
                if self.__location is None:
                    # Enabled?
                    if not self._app.config('app.localization.enabled', False):
                        raise RuntimeError('Location can not be used as localization is not enabled!')
                    if not self._app.config('app.localization.location.enabled', False):
                        raise RuntimeError('Location can not be used as it is not enabled!')

                    localization_manager = self._app.localization()
                    location_driver = localization_manager.location()
                    ip = self._request.remote_addr
                    self.__location = location_driver.insights(ip)
        return self.__location

    @property
    def localization(self):
        """
        Get localization information
        :return:    Localization info
        :rtype:     edmunds.localization.localization.models.localization.Localization
        """

        if self.__localization is None:
            with self.__localization_lock:
                if self.__localization is None:
                    # Enabled?
                    if not self._app.config('app.localization.enabled', False):
                        raise RuntimeError('Localization can not be used as it is not enabled!')

                    localization_manager = self._app.localization()
                    location = self.location
                    self.__localization = localization_manager.localization(location)
        return self.__localization
