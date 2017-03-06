
from edmunds.foundation.applicationmiddleware import ApplicationMiddleware
from werkzeug.debug import DebuggedApplication


class DebugMiddleware(ApplicationMiddleware):
    """
    Debug Middleware
    """

    def __init__(self, app):
        """
        Initialize the application
        :param app:     The application
        :type  app:     Application
        """

        super(DebugMiddleware, self).__init__(app)

        self.app.debug = True

        self.wsgi_app = DebuggedApplication(self.wsgi_app, True)

    def handle(self, environment, start_response):
        """
        Handle the middleware
        :param environment:     The environment
        :type  environment:     Environment
        :param start_response:   The application
        :type  start_response:   flask.Response
        """

        return super(DebugMiddleware, self).handle(environment, start_response)
