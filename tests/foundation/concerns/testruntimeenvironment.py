
from tests.testcase import TestCase


class TestRuntimeEnvironment(TestCase):
    """
    Test the Runtime Environment
    """

    def test_environment(self):
        """
        Test environment
        :return:    void
        """

        # Testing
        self.assert_equal('testing', self.app.environment())
        self.assert_false(self.app.environment('development'))
        self.assert_false(self.app.is_development())
        self.assert_true(self.app.environment('testing'))
        self.assert_true(self.app.is_testing())
        self.assert_false(self.app.environment('production'))
        self.assert_false(self.app.is_production())

        # Local
        self.app = self.create_application('development')
        self.assert_equal('development', self.app.environment())
        self.assert_true(self.app.environment('development'))
        self.assert_true(self.app.is_development())
        self.assert_false(self.app.environment('testing'))
        self.assert_false(self.app.is_testing())
        self.assert_false(self.app.environment('production'))
        self.assert_false(self.app.is_production())

        # Production
        self.app = self.create_application('production')
        self.assert_equal('production', self.app.environment())
        self.assert_false(self.app.environment('development'))
        self.assert_false(self.app.is_development())
        self.assert_false(self.app.environment('testing'))
        self.assert_false(self.app.is_testing())
        self.assert_true(self.app.environment('production'))
        self.assert_true(self.app.is_production())

    def test_is_gae(self):
        """
        Test is gae
        :return:    void
        """

        self.assert_false(self.app.is_gae())
