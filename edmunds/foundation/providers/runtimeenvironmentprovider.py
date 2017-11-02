
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

        # Correct the debug mode
        if 'FLASK_DEBUG' not in os.environ:
            if config_debug is not None:
                self.app.debug = config_debug
            elif self.app.is_development():
                self.app.debug = True

        # Testing environment
        if self.app.is_testing():
            self.app.testing = True
