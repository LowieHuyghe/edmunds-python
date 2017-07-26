
from edmunds.globals import request
from edmunds.http.input import Input
from edmunds.globals import make_response
from edmunds.cookie.cookies import Cookies
from threading import Lock


class Controller(object):
    """
    The Controller
    """

    def __init__(self, app):
        """
        Initialize the controller
        """

        self._app = app
        self._request = request
        self.__input = None
        self.__input_lock = Lock()
        self.__session = None
        self.__session_lock = Lock()
        self.__response = None
        self.__response_lock = Lock()
        self.__cookies = None
        self.__cookies_lock = Lock()

    def initialize(self, **params):
        """
        Initialize the controller
        :param params:      The arguments in the url
        :type  params:      dict
        """
        pass

    @property
    def _input(self):
        """
        Get input
        :return:    Input
        """

        if self.__input is None:
            with self.__input_lock:
                if self.__input is None:
                    self.__input = Input(self._request)
        return self.__input

    @_input.setter
    def _input(self, input):
        """
        Set input
        :param input:   Input 
        :return:        void
        """

        with self.__input_lock:
            self.__input = input

    @property
    def _session(self):
        """
        Get session
        :return:    Session
        """

        if self.__session is None:
            with self.__session_lock:
                if self.__session is None:
                    self.__session = self._app.session(no_instance_error=True)

        return self.__session

    @_session.setter
    def _session(self, session):
        """
        Set session
        :param session:     Session 
        :return:            void
        """

        with self.__session_lock:
            self.__session = session

    @property
    def _response(self):
        """
        Get response
        :return:    Response
        """

        if self.__response is None:
            with self.__response_lock:
                if self.__response is None:
                    self.__response = make_response()

        return self.__response

    @_response.setter
    def _response(self, response):
        """
        Set response
        :param response:    Response 
        :return:            void
        """

        with self.__response_lock:
            self.__response = response

    @property
    def _cookies(self):
        """
        Get cookies
        :return:    Cookies
        """

        if self.__cookies is None:
            with self.__cookies_lock:
                if self.__cookies is None:
                    self.__cookies = Cookies(self._request.cookies, self._response)

        return self.__cookies

    @_cookies.setter
    def _cookies(self, cookies):
        """
        Set cookies
        :param cookies:    Cookies 
        :return:            void
        """

        with self.__cookies_lock:
            self.__cookies = cookies
