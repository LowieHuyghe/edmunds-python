
class Localization(object):
    """
    This class concerns localization code for Application to extend from
    """

    def localization(self):
        """
        The localization manager
        :return:    The location manager
        :rtype:     edmunds.localization.localizationmanager.LocalizationManager
        """

        # Enabled?
        if not self.config('app.localization.enabled', False):
            return

        return self.extensions['edmunds.localization']
