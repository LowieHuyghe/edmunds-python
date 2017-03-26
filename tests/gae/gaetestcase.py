
from tests.testcase import TestCase
from edmunds.application import Application
import os
import sys
def gae_can_run():
    return sys.version_info < (3, 0)
if gae_can_run():
    from edmunds.gae.runtimeenvironment import RuntimeEnvironment as GaeRuntimeEnvironment
    from edmunds.gae.application import Application as GaeApplication
    from google.appengine.ext import testbed


class GaeTestCase(TestCase):

    @staticmethod
    def can_run():
        """
        Check if can run test
        :return:    Boolean
        """

        return gae_can_run()

    def set_up(self):
        """
        Set up the test case
        """

        if not self.can_run():
            self.skip("Google Cloud SDK doesn't run in Python 3+")

        self.testbed = testbed.Testbed()
        self.testbed.activate()

        super(GaeTestCase, self).set_up()

    def tear_down(self):
        """
        Tear down the test case
        """

        super(GaeTestCase, self).set_up()

        try:
            self.testbed.deactivate()
        except testbed.NotActivatedError:
            pass

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
