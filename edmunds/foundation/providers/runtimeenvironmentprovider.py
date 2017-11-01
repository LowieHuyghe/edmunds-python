
from edmunds.support.serviceprovider import ServiceProvider
import os


class RuntimeEnvironmentServiceProvider(ServiceProvider):
    """
    RuntimeEnvironment Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        config_debug = self.app.config('app.debug', None)
        env_debug = 'FLASK_DEBUG' in os.environ

        # Correct the debug mode
        if config_debug is None and not env_debug and self.app.is_development():
            self.app.debug = True

        # Testing environment
        if self.app.is_testing():
            self.app.testing = True
