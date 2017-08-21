
from edmunds.globals import abc, ABC


class ServiceProvider(ABC):
    """
    The Service Provider
    """

    def __init__(self, app):
        """
        Initialize the application
        :param app:     The application
        :type  app:     Edmunds.Application
        """

        self.app = app

    @abc.abstractmethod
    def register(self):
        """
        Register the service provider
        """
        pass
