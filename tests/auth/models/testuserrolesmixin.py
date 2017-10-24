
from tests.testcase import TestCase
from edmunds.auth.models.userrolesmixin import UserRolesMixin


class TestUserRolesMixin(TestCase):
    """
    Test user-roles mixin
    """

    def test_user_roles(self):
        """
        Test user_roles model
        :return:    void
        """

        self.assert_equal(2, len(UserRolesMixin))
        self.assert_in('user_id', UserRolesMixin[0].name)
        self.assert_in('role_id', UserRolesMixin[1].name)
