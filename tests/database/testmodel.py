
from tests.testcase import TestCase
from edmunds.database.model import Model, mapper, relationship, backref
from sqlalchemy.orm import mapper as sqlalchemy_mapper, relationship as sqlalchemy_relationship, backref as sqlalchemy_backref
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

        db = DatabaseManager.get_sql_alchemy_instance()
        model = Model()

        self.assert_is_instance(model, Model)
        self.assert_is_instance(model, object)
        self.assert_not_is_instance(model, db.Model)
        self.assert_not_equal(Model, db.Model)

        self.assert_equal_deep(sqlalchemy_mapper, mapper)
        self.assert_equal_deep(sqlalchemy_relationship, relationship)
        self.assert_equal_deep(sqlalchemy_backref, backref)
