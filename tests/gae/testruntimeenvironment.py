
from tests.testcase import TestCase
from edmunds.gae.runtimeenvironment import RuntimeEnvironment
from google.appengine.ext import testbed
from edmunds.application import Application
from edmunds.gae.application import Application as GaeApplication


class TestRuntimeEnvironment(TestCase):
    """
    Test the RuntimeEnvironment
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestRuntimeEnvironment, self).set_up()

        self._testbed = testbed.Testbed()

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestRuntimeEnvironment, self).tear_down()

        try:
            self._testbed.deactivate()
        except testbed.NotActivatedError:
            pass

    def test_is_gae(self):
        """
        Test is gae
        :return:    void
        """

        env = RuntimeEnvironment()

        self.assert_false(env.is_gae())

        self._testbed.activate()

        self.assert_true(env.is_gae())

    def test_testcase_app(self):
        """
        Test the testcase application
        :return:    void
        """

        self.assert_is_instance(self.create_application(), Application)

        self._testbed.activate()

        self.assert_is_instance(self.create_application(), GaeApplication)
