
from threading import Lock
from user_agents import parse


class Visitor(object):

    def __init__(self, request):
        """
        Constructor
        :param request: The request
        :type request:  edmunds.http.request.Request
        """

        self._request = request
        self.__client = None
        self.__client_lock = Lock()

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
