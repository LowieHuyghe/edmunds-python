
from tests.testcase import TestCase
from edmunds.gae.application import Application
from google.appengine.ext import testbed


class TestApplication(TestCase):
    """
    Test the Application
    """

    def set_up(self):
        """
        Set up the test case
        """

        self._testbed = testbed.Testbed()
        self._testbed.activate()
        self._testbed.init_app_identity_stub()

        super(TestApplication, self).set_up()

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestApplication, self).tear_down()

        self._testbed.deactivate()

    def test_app_id(self):
        """
        Test app id
        :return:    void
        """

        app_id = self.app.app_id()

        self.assert_is_not_none(app_id)
        self.assert_in('testbed', app_id)
