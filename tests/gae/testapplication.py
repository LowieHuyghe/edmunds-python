
from tests.gae.gaetestcase import GaeTestCase


class TestApplication(GaeTestCase):
    """
    Test the Application
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestApplication, self).set_up()

        self.testbed.init_app_identity_stub()

    def test_app_id(self):
        """
        Test app id
        :return:    void
        """

        app_id = self.app.app_id()

        self.assert_is_not_none(app_id)
        self.assert_in('testbed', app_id)
