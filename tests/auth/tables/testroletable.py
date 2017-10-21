
from tests.testcase import TestCase
from edmunds.auth.tables.roletable import RoleTable
from edmunds.database.table import Table, Column, Integer


class TestRoleTable(TestCase):

    def test_role_table(self):
        """
        Test role table
        :return:    void
        """

        test_table = Table('test_role', Column('id', Integer, primary_key=True))

        self.assert_is_not_none(RoleTable)
        self.assert_is_instance(RoleTable, test_table.__class__)
        self.assert_equal_deep('role', RoleTable.name)
