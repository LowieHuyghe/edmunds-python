
from tests.gae.gaetestcase import GaeTestCase
from edmunds.application import Application
import os
if GaeTestCase.can_run():
    from edmunds.gae.runtimeenvironment import RuntimeEnvironment
    from edmunds.gae.application import Application as GaeApplication


class TestRuntimeEnvironment(GaeTestCase):
    """
    Test the RuntimeEnvironment
    """

    def test_is_gae(self):
        """
        Test is gae
        :return:    void
        """

        env = RuntimeEnvironment()

        self.assert_true(env.is_gae())

        self.testbed.deactivate()

        self.assert_false(env.is_gae())

        self.testbed.activate()

        self.assert_true(env.is_gae())

    def test_is_gae_env(self):
        """
        Test is gae with env
        :return:    void
        """

        self.testbed.deactivate()
        env = RuntimeEnvironment()
        self.assert_false(env.is_gae())

        os.environ['CURRENT_VERSION_ID'] = self.rand_str()
        self.assert_false(env.is_gae())

        os.environ['AUTH_DOMAIN'] = self.rand_str()
        self.assert_false(env.is_gae())

        os.environ['SERVER_SOFTWARE'] = self.rand_str()
        self.assert_false(env.is_gae())

        os.environ['SERVER_SOFTWARE'] = 'Development/%s' % self.rand_str()
        self.assert_true(env.is_gae())

        os.environ['SERVER_SOFTWARE'] = 'Google App Engine/%s' % self.rand_str()
        self.assert_true(env.is_gae())

    def test_testcase_app(self):
        """
        Test the testcase application
        :return:    void
        """

        self.assert_is_instance(self.create_application(), GaeApplication)

        self.testbed.deactivate()

        self.assert_is_instance(self.create_application(), Application)

        self.testbed.activate()

        self.assert_is_instance(self.create_application(), GaeApplication)
