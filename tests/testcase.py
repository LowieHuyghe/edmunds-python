
from edmunds.foundation.testing.testcase import TestCase as EdmundsTestCase
from edmunds.application import Application
import os


class TestCase(EdmundsTestCase):

    def create_application(self, environment='testing'):
        """
        Create the application for testing
        :param environment:
        :return:  Application
        """

        os.environ['APP_ENV'] = environment

        return Application('')
