
from flask import request
from edmunds.http.input import Input
from flask import has_request_context, make_response
from edmunds.cookie.cookies import Cookies


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
        self._session = None
        self._response = None
        self._cookies = None

        if has_request_context():
            self._session = app.session(no_instance_error=True)
            self._response = make_response()
            self._cookies = Cookies(request.cookies, self._response)

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
            self.__input = Input(self._request)
        return self.__input

    @_input.setter
    def _input(self, input):
        """
        Set input
        :param input:   Input 
        :return:        void
        """

        self.__input = input
