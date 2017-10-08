
from edmunds.gae.gaetestcase import GaeTestCase as EdmundsGaeTestCase, gae_can_run
from edmunds.application import Application
import os
if gae_can_run():
    from edmunds.gae.runtimeenvironment import RuntimeEnvironment as GaeRuntimeEnvironment
    from edmunds.gae.application import Application as GaeApplication


class GaeTestCase(EdmundsGaeTestCase):

    def create_application(self, environment='testing'):
        """
        Create the application for testing
        :param environment:
        :return:  Application
        """

        os.environ['APP_ENV'] = environment

        if GaeRuntimeEnvironment().is_gae():
            return GaeApplication('')

        return Application('')
