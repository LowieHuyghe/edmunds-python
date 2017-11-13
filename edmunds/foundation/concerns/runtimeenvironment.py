
from edmunds.gae.runtimeenvironment import RuntimeEnvironment as GaeRuntimeEnvironment


class RuntimeEnvironment(object):
    """
    This class concerns runtime-environment code for Application to extend from
    """

    def environment(self, matches=None):
        """
        Get the environment
        :param matches:     Environment to match with
        :type  matches:     str
        :return:            The environment or checks the given environment
        :rtype:             str|boolean
        """

        environment = self.config['APP_ENV']

        if matches is None:
            return environment
        else:
            return environment == matches

    def is_development(self):
        """
        Check if running in development environment
        """

        return self.environment('development')

    def is_testing(self):
        """
        Check if running in testing environment
        """

        return self.environment('testing')

    def is_production(self):
        """
        Check if running in production environment
        """

        return self.environment('production')

    def is_gae(self):
        """
        Check if running in Google App Engine
        """

        if not hasattr(self, '_is_gae'):
            self._is_gae = GaeRuntimeEnvironment.is_gae()

        return self._is_gae

    def is_gae_development(self):
        """
        Check if running in Google App Engine SDK
        """

        if not hasattr(self, '_is_gae_development'):
            self._is_gae_development = GaeRuntimeEnvironment.is_gae_development()

        return self._is_gae_development

    def app_id(self):
        """
        Get the Google App Engine app id
        :return:    The app id
        :rtype:     str
        """

        if not self.is_gae():
            raise RuntimeError('Not running in Google App Engine environment while fetching app_id.')

        if not hasattr(self, '_app_id'):
            from google.appengine.api import app_identity
            self._app_id = app_identity.get_application_id()

        return self._app_id
