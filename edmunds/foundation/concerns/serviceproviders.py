
from threading import Lock


class ServiceProviders(object):
    """
    This class concerns service providers code for Application to extend from
    """

    def register(self, class_):
        """
        Register a Service Provider
        :param class_:  The class of the provider
        :type  class_:  ServiceProvider
        """

        lock_key = 'edmunds.serviceprovider.lock'
        providers_key = 'edmunds.serviceprovider.providers'

        # Register the lock
        if lock_key not in self.extensions:
            self.extensions[lock_key] = Lock()

        # Define list to register providers
        if providers_key not in self.extensions:
            with self.extensions[lock_key]:
                if providers_key not in self.extensions:
                    self.extensions[providers_key] = []

        # Only register a provider once
        if class_ in self.extensions[providers_key]:
            return
        self.extensions[providers_key].append(class_)

        service_provider = class_(self)
        service_provider.register()
