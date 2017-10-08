
from edmunds.foundation.testing.testcase import TestCase
import sys
def gae_can_run():
    return sys.version_info < (3, 0)
if gae_can_run():
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

        super(GaeTestCase, self).tear_down()

        try:
            self.testbed.deactivate()
        except testbed.NotActivatedError:
            pass
