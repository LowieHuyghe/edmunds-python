
from flask import Flask
from edmunds.foundation.concerns.config import Config as ConcernsConfig
from edmunds.foundation.concerns.runtimeenvironment import RuntimeEnvironment as ConcernsRuntimeEnvironment
from edmunds.foundation.concerns.serviceproviders import ServiceProviders as ConcernsServiceProviders
from edmunds.foundation.concerns.middleware import Middleware as ConcernsMiddleware
from edmunds.foundation.concerns.storage import Storage as ConcernsStorage
from edmunds.foundation.concerns.session import Session as ConcernsSession
from edmunds.foundation.concerns.database import Database as ConcernsDatabase
from edmunds.foundation.concerns.localization import Localization as ConcernsLocalization
from edmunds.foundation.concerns.cache import Cache as ConcernsCache
from edmunds.foundation.concerns.auth import Auth as ConcernsAuth
from edmunds.exceptions.exceptionsserviceprovider import ExceptionsServiceProvider
from edmunds.log.providers.logserviceprovider import LogServiceProvider
from edmunds.session.providers.sessionserviceprovider import SessionServiceProvider
from edmunds.storage.providers.storageserviceprovider import StorageServiceProvider
from edmunds.database.providers.databaseserviceprovider import DatabaseServiceProvider
from edmunds.localization.providers.localizationserviceprovider import LocalizationServiceProvider
from edmunds.cache.providers.cacheserviceprovider import CacheServiceProvider
from edmunds.http.providers.httpserviceprovider import HttpServiceProvider
from edmunds.auth.providers.authserviceprovider import AuthServiceProvider
from edmunds.foundation.providers.runtimeenvironmentprovider import RuntimeEnvironmentServiceProvider
from edmunds.profiler.providers.profilerserviceprovider import ProfilerServiceProvider
from edmunds.config.config import Config
from edmunds.http.request import Request
from edmunds.http.response import Response
from edmunds.http.route import Route


class Application(Flask,
                  ConcernsConfig,
                  ConcernsRuntimeEnvironment,
                  ConcernsServiceProviders,
                  ConcernsMiddleware,
                  ConcernsStorage,
                  ConcernsSession,
                  ConcernsDatabase,
                  ConcernsLocalization,
                  ConcernsCache,
                  ConcernsAuth):
    """
    The Edmunds Application
    """

    request_class = Request
    response_class = Response
    config_class = Config

    def __init__(self, import_name, config_dirs=None, *args, **kwargs):
        """
        Initialize the application
        :param import_name:     Import name
        :type  import_name:     str
        :param config_dirs:     Configuration directories
        :type  config_dirs:     list
        :param args:            Additional args
        :type  args:            list
        :param kwargs:          Additional kwargs
        :type  kwargs:          dict
        """

        super(Application, self).__init__(import_name, *args, **kwargs)

        self.logger_name = 'edmunds.%s' % import_name

        self._init_config(config_dirs)
        self._init_database()

        self.register(RuntimeEnvironmentServiceProvider)
        self.register(ProfilerServiceProvider)
        self.register(HttpServiceProvider)
        self.register(StorageServiceProvider)
        self.register(ExceptionsServiceProvider)
        self.register(LogServiceProvider)
        self.register(SessionServiceProvider)
        self.register(DatabaseServiceProvider)
        self.register(CacheServiceProvider)
        self.register(LocalizationServiceProvider)
        self.register(AuthServiceProvider)

    def route(self, rule, **options):
        """
        Register a route
        This van be done the old skool way, or with the uses
        :param rule:    The rule for routing the request
        :type  rule:    str
        :param options: List of options
        :type  options: list
        :return:        Route instance or decorator function
        :rtype:         edmunds.http.route.Route
        """

        route = Route(self)

        # Register middleware that was given with the options
        if 'middleware' in options:
            for middleware in options.pop('middleware'):
                if isinstance(middleware, tuple):
                    route.middleware(middleware[0], *middleware[1:])
                else:
                    route.middleware(middleware)

        if 'uses' in options:
            # Add controller and method
            controller_class, method_name = options.pop('uses')
            route.uses(controller_class, method_name)

            return_value = route
        else:
            return_value = route.decorate

        # Define endpoint
        if 'endpoint' in options:
            endpoint = options.pop('endpoint')
        else:
            endpoint = 'edmunds.route.%s' % rule

        # Add route
        self.add_url_rule(rule, endpoint=endpoint, view_func=route.handle)

        return return_value
