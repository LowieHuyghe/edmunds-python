
from flask import Flask
from edmunds.foundation.concerns.config import Config as ConcernsConfig
from edmunds.foundation.concerns.runtimeenvironment import RuntimeEnvironment as ConcernsRuntimeEnvironment
from edmunds.foundation.concerns.serviceproviders import ServiceProviders as ConcernsServiceProviders
from edmunds.foundation.concerns.middleware import Middleware as ConcernsMiddleware
from edmunds.foundation.concerns.requestrouting import RequestRouting as ConcernsRequestRouting
from edmunds.foundation.concerns.storage import Storage as ConcernsStorage
from edmunds.foundation.concerns.session import Session as ConcernsSession
from edmunds.exceptions.exceptionsserviceprovider import ExceptionsServiceProvider
from edmunds.log.providers.logserviceprovider import LogServiceProvider
from edmunds.config.config import Config
from threading import Lock

_logger_lock = Lock()


class Application(Flask, ConcernsConfig, ConcernsRuntimeEnvironment, ConcernsServiceProviders, ConcernsMiddleware, ConcernsRequestRouting, ConcernsStorage, ConcernsSession):
    """
    The Edmunds Application
    """

    config_class = Config

    def __init__(self, import_name, config_dirs=None):
        """
        Initialize the application
        :param import_name:     Import name
        :type  import_name:     str
        :param config_dirs:     Configuration directories
        :type  config_dirs:     list
        """

        super(Application, self).__init__(import_name)

        self._init_config(config_dirs)
        self._init_storage()
        self._init_service_providers()
        self._init_middleware()
        self._init_request_routing()
        self._init_runtime_environment()
        self._init_session()

        self.register(ExceptionsServiceProvider)
        if self.config('app.log.enabled', False):
            self.register(LogServiceProvider)

    def route(self, rule, **options):
        """
        Register a route
        This is merely a step to abstract the middleware from the route
        :param rule:    The rule for routing the request
        :type  rule:    str
        :param options: List of options
        :type  options: list
        :return:        Decorator function
        :rtype:         function
        """

        # Pre handline
        self._pre_handle_route_dispatching(rule, options)
        self._pre_handle_route_middleware(rule, options)

        # Fetch the decorator function
        decorator = super(Application, self).route(rule, **options)

        # Post handline
        decorator = self._post_handle_route_middleware(decorator, rule, options)
        decorator = self._post_handle_route_dispatching(decorator, rule, options)

        return decorator

    @property
    def logger(self):
        """
        Fetch logger property
        Overriding this function because self.logger_name == '' is
        not taken into account
        """

        if not self.logger_name:
            if self._logger and self._logger.name == 'root':
                return self._logger
            with _logger_lock:
                if self._logger and self._logger.name == 'root':
                    return self._logger
                from flask.logging import create_logger
                self._logger = rv = create_logger(self)
                return rv

        else:
            return super(Application, self).logger
