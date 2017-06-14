
from tests.testcase import TestCase
from edmunds.database.table import db, Table, Column, ForeignKey, BigInteger, Boolean, Date, DateTime, Enum, Float, Integer, Interval, LargeBinary, Numeric, PickleType, SmallInteger, String, Text, Time, Unicode, UnicodeText
from edmunds.database.databasemanager import DatabaseManager


class TestTable(TestCase):
    """
    Test the table
    """

    def test_table(self):
        """
        Test table
        :return:    void
        """

        test_db = DatabaseManager.get_sql_alchemy_instance()

        self.assert_equal_deep(test_db, db)
        self.assert_equal_deep(test_db.Table, Table)
        self.assert_equal_deep(test_db.Column, Column)
        self.assert_equal_deep(test_db.ForeignKey, ForeignKey)

        self.assert_equal_deep(test_db.BigInteger, BigInteger)
        self.assert_equal_deep(test_db.Boolean, Boolean)
        self.assert_equal_deep(test_db.Date, Date)
        self.assert_equal_deep(test_db.DateTime, DateTime)
        self.assert_equal_deep(test_db.Enum, Enum)
        self.assert_equal_deep(test_db.Float, Float)
        self.assert_equal_deep(test_db.Integer, Integer)
        self.assert_equal_deep(test_db.Interval, Interval)
        self.assert_equal_deep(test_db.LargeBinary, LargeBinary)
        self.assert_equal_deep(test_db.Numeric, Numeric)
        self.assert_equal_deep(test_db.PickleType, PickleType)
        self.assert_equal_deep(test_db.SmallInteger, SmallInteger)
        self.assert_equal_deep(test_db.String, String)
        self.assert_equal_deep(test_db.Text, Text)
        self.assert_equal_deep(test_db.Time, Time)
        self.assert_equal_deep(test_db.Unicode, Unicode)
        self.assert_equal_deep(test_db.UnicodeText, UnicodeText)
