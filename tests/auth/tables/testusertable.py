
from tests.testcase import TestCase
from edmunds.auth.tables.usertable import UserTable
from edmunds.database.table import Table, Column, Integer


class TestUserTable(TestCase):

    def test_user_table(self):
        """
        Test user table
        :return:    void
        """

        test_table = Table('test_user', Column('id', Integer, primary_key=True))

        self.assert_is_not_none(UserTable)
        self.assert_is_instance(UserTable, test_table.__class__)
        self.assert_equal_deep('user', UserTable.name)
