
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.profiler.middleware.profilermiddleware import ProfilerMiddleware


class ProfilerServiceProvider(ServiceProvider):
    """
    Profiler Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        if not self.app.debug or not self.app.config('app.profiler.enabled', False):
            return

        self.app.middleware(ProfilerMiddleware)
