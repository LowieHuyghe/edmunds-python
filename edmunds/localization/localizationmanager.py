
class LocalizationManager(object):

    def __init__(self, app):
        """
        Constructor
        :param app: The app 
        """
        self._app = app

    def location(self):
        """
        The location manager
        :return:    The location manager
        :rtype:     edmunds.localization.location.locationmanager.LocationManager
        """

        # Enabled?
        if not self._app.config('app.localization.location.enabled', False):
            return None

        # Return manager
        return self._app.extensions['edmunds.localization.location']
