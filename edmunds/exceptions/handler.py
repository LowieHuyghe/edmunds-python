
from werkzeug.exceptions import HTTPException


class Handler(object):
    """
    The Exception handler
    """

    def __init__(self, app):
        """
        Initiate
        :param app:     The application
        :type  app:     Edmunds.Application
        """

        self.app = app
        self.dont_report = []

    def report(self, exception):
        """
        Report the exception
        :param exception:   The exception
        :type  exception:   Exception
        """

        if exception.__class__ in self.dont_report:
            return;

        pass

    def render(self, exception):
        """
        Render the exception
        :param exception:   The exception
        :type  exception:   Exception
        :return:            The response
        """

        # Determine status code
        status_code = 500
        if isinstance(exception, HTTPException):
            status_code = exception.code

        if self.app.debug:
            raise exception
        else:
            return str(status_code), status_code
