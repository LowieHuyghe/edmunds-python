
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
        self._input = Input(self._request)

    def initialize(self, **params):
        """
        Initialize the controller
        :param params:      The arguments in the url
        :type  params:      dict
        """
        pass
