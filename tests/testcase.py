
from edmunds.foundation.testing.testcase import TestCase as EdmundsTestCase
from edmunds.gae.runtimeenvironment import RuntimeEnvironment as GaeRuntimeEnvironment
from edmunds.application import Application
from edmunds.gae.application import Application as GaeApplication
import os


class TestCase(EdmundsTestCase):

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
