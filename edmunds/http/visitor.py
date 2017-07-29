
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
        :rtype:     user_agents.parsers.UserAgent
        """

        if self.__location is None:
            with self.__location_lock:
                if self.__location is None:
                    localization_manager = self._app.localization()
                    location_manager = localization_manager.location()
                    location_driver = location_manager.get()
                    ip = self._request.remote_addr
                    self.__location = location_driver.insights(ip)
        return self.__location
