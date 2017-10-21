
from tests.testcase import TestCase
from edmunds.auth.tables.userrolestable import UserRolesTable
from edmunds.database.table import Table, Column, Integer


class TestUserRolesTable(TestCase):

    def test_userroles_table(self):
        """
        Test userroles table
        :return:    void
        """

        test_table = Table('test_userroles', Column('id', Integer, primary_key=True))

        self.assert_is_not_none(UserRolesTable)
        self.assert_is_instance(UserRolesTable, test_table.__class__)
        self.assert_equal('user_roles', UserRolesTable.name)
