
class LocalizationManager(object):

    def __init__(self, app):
        """
        Constructor
        :param app: The app 
        """
        self._app = app

    def location(self, name=None, no_instance_error=False):
        """
        The location manager
        :param name:                The name of the session instance
        :type  name:                str
        :param no_instance_error:   Error when no instance
        :type  no_instance_error:   bool
        :return:                    A location driver
        :rtype:                     edmunds.localization.location.drivers.basedriver.BaseDriver
        """

        # Enabled?
        if not self._app.config('app.localization.location.enabled', False):
            return None

        # Return driver
        return self._app.extensions['edmunds.localization.location'].get(name, no_instance_error=no_instance_error)
