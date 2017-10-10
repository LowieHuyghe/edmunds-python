
from werkzeug.exceptions import HTTPException
import sys
from six import reraise


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
        :return:            Should report
        """

        if exception.__class__ in self.dont_report:
            return False

        return True

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
        is_error_code = status_code - (status_code % 100) == 500

        if self.app.debug and is_error_code:
            if sys.version_info < (3, 0):
                exc_type, exc_value, tb = sys.exc_info()
                if exc_value is exception:
                    reraise(exc_type, exc_value, tb)
            raise exception
        elif isinstance(exception, HTTPException):
            return exception.get_response()
        elif self.app.testing and is_error_code:
            return str(status_code), '%s' % exception
        else:
            return str(status_code), status_code
