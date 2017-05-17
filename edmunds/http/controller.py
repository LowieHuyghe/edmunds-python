
from flask import request
from edmunds.http.input import Input


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
