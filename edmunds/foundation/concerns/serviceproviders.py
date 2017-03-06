
class ServiceProviders(object):
    """
    This class concerns service providers code for Application to extend from
    """

    def _init_service_providers(self):
        """
        Initialise concerning service providers
        """

        self._registered_service_providers = []

    def register(self, class_):
        """
        Register a Service Provider
        :param class_:  The class of the provider
        :type  class_:  ServiceProvider
        """

        # Only register a provider once
        if class_ in self._registered_service_providers:
            return
        self._registered_service_providers.append(class_)

        service_provider = class_(self)
        service_provider.register()
