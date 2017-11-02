
from edmunds.globals import request, visitor
from edmunds.http.input import Input
from edmunds.http.responsehelper import ResponseHelper
from threading import Lock


class Controller(object):
    """
    The Controller
    """

    def __init__(self, app):
        """
        Initialize the controller
        :param app: The application
        :type  app: edmunds.application.Application
        """

        self.app = app
        self.request = request
        self.visitor = visitor
        self._input = None
        self._input_lock = Lock()
        self._session = None
        self._session_lock = Lock()
        self._response = None
        self._response_lock = Lock()

    def initialize(self, **params):
        """
        Initialize the controller
        :param params:      The arguments in the url
        :type  params:      dict
        """
        pass

    @property
    def input(self):
        """
        Get input
        :return:    Input
        :type:      edmunds.http.input.Input
        """

        if self._input is None:
            with self._input_lock:
                if self._input is None:
                    self._input = Input(self.request)
        return self._input

    @input.setter
    def input(self, input):
        """
        Set input
        :param input:   Input 
        :return:        void
        """

        with self._input_lock:
            self._input = input

    @property
    def session(self):
        """
        Get session
        :return:    Session
        """

        if self._session is None:
            with self._session_lock:
                if self._session is None:
                    self._session = self.app.session(no_instance_error=True)

        return self._session

    @session.setter
    def session(self, session):
        """
        Set session
        :param session:     Session 
        :return:            void
        """

        with self._session_lock:
            self._session = session

    @property
    def response(self):
        """
        Get response
        :return:    Response Helper
        :rtype:     edmunds.http.responsehelper.ResponseHelper
        """

        if self._response is None:
            with self._response_lock:
                if self._response is None:
                    self._response = ResponseHelper()

        return self._response

    @response.setter
    def response(self, response):
        """
        Set response
        :param response:    Response Helper
        :return:            void
        """

        with self._response_lock:
            self._response = response
