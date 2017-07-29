
from edmunds.foundation.testing.testcase import TestCase as EdmundsTestCase
from edmunds.application import Application
import os


class TestCase(EdmundsTestCase):

    def create_application(self, environment='testing', config_dirs=None):
        """
        Create the application for testing
        :param environment: Environment
        :param config_dirs: Config dirs
        :return:            edmunds.application.Application
        """

        os.environ['APP_ENV'] = environment

        return Application('', config_dirs=config_dirs)
