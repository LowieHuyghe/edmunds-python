
from edmunds.globals import abc, ABC


class ApplicationMiddleware(ABC):
    """
    The Application Middleware
    """

    def __init__(self, app):
        """
        Initialize the application
        :param app:     The application
        :type  app:     Application
        """

        self.app = app
        self.wsgi_app = app.wsgi_app

    def __call__(self, environment, start_response):
        """
        Incoming call of middleware
        :param environment:     The environment
        :type  environment:     Application
        :param start_response:   The application
        :type  start_response:   Application
        """

        return self.handle(environment, start_response)

    @abc.abstractmethod
    def handle(self, environment, start_response):
        """
        Handle the middleware
        :param environment:     The environment
        :type  environment:     Application
        :param start_response:   The application
        :type  start_response:   Application
        """

        return self.wsgi_app(environment, start_response)
