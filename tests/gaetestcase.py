
from edmunds.gae.gaetestcase import GaeTestCase as EdmundsGaeTestCase, gae_can_run
from edmunds.application import Application
import os


class GaeTestCase(EdmundsGaeTestCase):

    def create_application(self, environment='testing'):
        """
        Create the application for testing
        :param environment: Environment
        :return:            Application
        :rtype:             edmunds.application.Application
        """

        os.environ['APP_ENV'] = environment

        return Application('')
