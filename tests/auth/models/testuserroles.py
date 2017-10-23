
from tests.testcase import TestCase
from edmunds.database.model import Table, Column, Integer
from edmunds.auth.models.userroles import UserRolesTable


class TestUserRoles(TestCase):
    """
    Test user-roles
    """

    def test_user_roles(self):
        """
        Test user_roles model
        :return:    void
        """

        dummy_table = Table('test', Column('id', Integer))
        self.assert_is_instance(UserRolesTable, dummy_table.__class__)

        self.assert_equal(2, len(UserRolesTable.columns))
        self.assert_in('user_id', UserRolesTable.columns)
        self.assert_in('role_id', UserRolesTable.columns)
