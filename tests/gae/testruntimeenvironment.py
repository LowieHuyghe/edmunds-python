
import os

from edmunds.gae.runtimeenvironment import RuntimeEnvironment
from tests.gaetestcase import GaeTestCase


class TestRuntimeEnvironment(GaeTestCase):
    """
    Test the RuntimeEnvironment
    """

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestRuntimeEnvironment, self).tear_down()

        if 'CURRENT_VERSION_ID' in os.environ:
            del os.environ['CURRENT_VERSION_ID']
        if 'AUTH_DOMAIN' in os.environ:
            del os.environ['AUTH_DOMAIN']
        if 'SERVER_SOFTWARE' in os.environ:
            del os.environ['SERVER_SOFTWARE']

    def test_is_gae(self):
        """
        Test is gae
        :return:    void
        """

        self.assert_true(RuntimeEnvironment.is_gae())
        self.assert_true(self.create_application().is_gae())

        self.testbed.deactivate()

        self.assert_false(RuntimeEnvironment.is_gae())
        self.assert_false(self.create_application().is_gae())

        self.testbed.activate()

        self.assert_true(RuntimeEnvironment.is_gae())
        self.assert_true(self.create_application().is_gae())

    def test_is_gae_env(self):
        """
        Test is gae with env
        :return:    void
        """

        self.testbed.deactivate()
        self.assert_false(RuntimeEnvironment.is_gae())

        os.environ['CURRENT_VERSION_ID'] = self.rand_str()
        self.assert_false(RuntimeEnvironment.is_gae())

        os.environ['AUTH_DOMAIN'] = self.rand_str()
        self.assert_false(RuntimeEnvironment.is_gae())

        os.environ['SERVER_SOFTWARE'] = self.rand_str()
        self.assert_false(RuntimeEnvironment.is_gae())

        os.environ['SERVER_SOFTWARE'] = 'Development/%s' % self.rand_str()
        self.assert_true(RuntimeEnvironment.is_gae())

        os.environ['SERVER_SOFTWARE'] = 'Google App Engine/%s' % self.rand_str()
        self.assert_true(RuntimeEnvironment.is_gae())
