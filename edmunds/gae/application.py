
from edmunds.application import Application as EdmundsApplication
from google.appengine.api import app_identity


class Application(EdmundsApplication):
    """
    The Google App Engine Edmunds Application
    """

    def app_id(self):
        """
        Get the app id
        :return:    The app id
        :rtype:     str
        """

        return app_identity.get_application_id()
