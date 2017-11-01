
from tests.testcase import TestCase
from edmunds.database.db import db, mapper, relationship, backref
from sqlalchemy.orm import mapper as sqlalchemy_mapper, relationship as sqlalchemy_relationship, backref as sqlalchemy_backref
from edmunds.database.databasemanager import DatabaseManager
from werkzeug.local import LocalProxy
from flask_sqlalchemy import SQLAlchemy


class TestModel(TestCase):
    """
    Test the model
    """

    def test_model(self):
        """
        Test model
        :return:    void
        """

        test_db = DatabaseManager.get_sql_alchemy_instance()

        self.assert_is_instance(db, LocalProxy)
        self.assert_is_instance(db._get_current_object(), SQLAlchemy)
        self.assert_equal_deep(test_db, db._get_current_object())

        self.assert_equal_deep(sqlalchemy_mapper, mapper)
        self.assert_equal_deep(sqlalchemy_relationship, relationship)
        self.assert_equal_deep(sqlalchemy_backref, backref)
