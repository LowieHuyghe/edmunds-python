
from tests.testcase import TestCase
from edmunds.database.model import Model
from edmunds.database.databasemanager import DatabaseManager


class TestModel(TestCase):
    """
    Test the model
    """

    def test_model(self):
        """
        Test model
        :return:    void
        """

        self.assert_equal_deep(DatabaseManager.get_sql_alchemy_instance().Model, Model)
