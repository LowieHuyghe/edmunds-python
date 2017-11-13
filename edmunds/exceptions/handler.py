
from werkzeug.exceptions import HTTPException, InternalServerError
import sys
from six import reraise


class Handler(object):
    """
    The Exception handler
    """

    dont_report = []

    def __init__(self, app):
        """
        Initiate
        :param app:     The application
        :type  app:     edmunds.application.Application
        """

        self.app = app

    def report(self, exception):
        """
        Report the exception
        :param exception:   The exception
        :type  exception:   Exception
        :return:            Should report
        """

        for exception_class in self.dont_report:
            if isinstance(exception, exception_class):
                return False

        self.app.logger.critical(exception, exc_info=sys.exc_info())

        return True

    def render(self, exception):
        """
        Render the exception
        :param exception:   The exception
        :type  exception:   Exception
        :return:            The response
        """

        if not isinstance(exception, HTTPException):
            http_exception = InternalServerError()
        else:
            http_exception = exception
        is_server_error = http_exception.code - (http_exception.code % 100) == 500

        if self.app.debug and is_server_error:
            if sys.version_info < (3, 0):
                exc_type, exc_value, tb = sys.exc_info()
                if exc_value is exception:
                    reraise(exc_type, exc_value, tb)
            raise exception
        else:
            if self.app.testing and is_server_error and isinstance(exception, Exception):
                http_exception.description = '%s' % exception
            return http_exception.get_response()
