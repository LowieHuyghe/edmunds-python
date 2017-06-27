
from tests.testcase import TestCase
from edmunds.database.model import Model, mapper, relationship, backref
from edmunds.database.table import Table, Column, Integer, String
from sqlalchemy.orm import mapper as sqlalchemy_mapper, relationship as sqlalchemy_relationship, backref as sqlalchemy_backref
from edmunds.database.databasemanager import DatabaseManager
from sqlalchemy.orm.scoping import scoped_session
from sqlalchemy.orm.query import Query


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

    def test_session(self):
        """
        Test session function
        :return:    void
        """

        rule = self.rand_str(20)

        # Write config
        self.write_config([
            "from edmunds.database.drivers.mysql import MySql \n",
            "APP = { \n",
            "   'database': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'mysql',\n",
            "               'driver': MySql,\n",
            "               'user': 'root',\n",
            "               'pass': 'root',\n",
            "               'host': 'localhost',\n",
            "               'database': 'edmunds',\n",
            "           }, \n",
            "           { \n",
            "               'name': 'mysql2',\n",
            "               'driver': MySql,\n",
            "               'user': 'root',\n",
            "               'pass': 'root',\n",
            "               'host': 'localhost',\n",
            "               'database': 'edmunds2',\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()

        with app.test_request_context(rule):
            # Test session of MyModel
            self.assert_is_not_none(MyModel.session())
            self.assert_is_instance(MyModel.session(), scoped_session)
            self.assert_is_not_none(MyModel.session('mysql'))
            self.assert_is_instance(MyModel.session('mysql'), scoped_session)
            self.assert_is_not_none(MyModel.session('mysql2'))
            self.assert_is_instance(MyModel.session('mysql2'), scoped_session)
            with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
                MyModel.session('mysql3')
            self.assert_is_none(MyModel.session('mysql3', no_instance_error=True))

            self.assert_equal_deep(MyModel.session(), MyModel.session())
            self.assert_equal_deep(MyModel.session(), MyModel.session('mysql'))
            self.assert_not_equal(MyModel.session(), MyModel.session('mysql2'))

            # Test session of MySecondModel
            self.assert_is_not_none(MySecondModel.session())
            self.assert_is_instance(MySecondModel.session(), scoped_session)
            self.assert_is_not_none(MySecondModel.session('mysql'))
            self.assert_is_instance(MySecondModel.session('mysql'), scoped_session)
            self.assert_is_not_none(MySecondModel.session('mysql2'))
            self.assert_is_instance(MySecondModel.session('mysql2'), scoped_session)
            with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
                MySecondModel.session('mysql3')
            self.assert_is_none(MySecondModel.session('mysql3', no_instance_error=True))

            self.assert_equal_deep(MySecondModel.session(), MySecondModel.session())
            self.assert_not_equal(MySecondModel.session(), MySecondModel.session('mysql'))
            self.assert_equal_deep(MySecondModel.session(), MySecondModel.session('mysql2'))

    def test_query(self):
        """
        Test query function
        :return:    void
        """

        rule = self.rand_str(20)

        # Write config
        self.write_config([
            "from edmunds.database.drivers.mysql import MySql \n",
            "APP = { \n",
            "   'database': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'mysql',\n",
            "               'driver': MySql,\n",
            "               'user': 'root',\n",
            "               'pass': 'root',\n",
            "               'host': 'localhost',\n",
            "               'database': 'edmunds',\n",
            "           }, \n",
            "           { \n",
            "               'name': 'mysql2',\n",
            "               'driver': MySql,\n",
            "               'user': 'root',\n",
            "               'pass': 'root',\n",
            "               'host': 'localhost',\n",
            "               'database': 'edmunds2',\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()

        with app.test_request_context(rule):
            # Test session of MyModel
            self.assert_is_not_none(MyModel.query())
            self.assert_is_instance(MyModel.query(), Query)
            self.assert_is_not_none(MyModel.query('mysql'))
            self.assert_is_instance(MyModel.query('mysql'), Query)
            self.assert_is_not_none(MyModel.query('mysql2'))
            self.assert_is_instance(MyModel.query('mysql2'), Query)
            with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
                MyModel.query('mysql3')
            self.assert_is_none(MyModel.query('mysql3', no_instance_error=True))

            self.assert_not_equal(MyModel.query(), MyModel.query())
            self.assert_not_equal(MyModel.query(), MyModel.query('mysql'))
            self.assert_not_equal(MyModel.query(), MyModel.query('mysql2'))

            # Test session of MySecondModel
            self.assert_is_not_none(MySecondModel.query())
            self.assert_is_instance(MySecondModel.query(), Query)
            self.assert_is_not_none(MySecondModel.query('mysql'))
            self.assert_is_instance(MySecondModel.query('mysql'), Query)
            self.assert_is_not_none(MySecondModel.query('mysql2'))
            self.assert_is_instance(MySecondModel.query('mysql2'), Query)
            with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
                MySecondModel.query('mysql3')
            self.assert_is_none(MySecondModel.query('mysql3', no_instance_error=True))

            self.assert_not_equal(MySecondModel.query(), MySecondModel.query())
            self.assert_not_equal(MySecondModel.query(), MySecondModel.query('mysql'))
            self.assert_not_equal(MySecondModel.query(), MySecondModel.query('mysql2'))


MyModelTable = Table('mymodels',
                     Column('id', Integer, primary_key=True),
                     Column('name', String(50)),
                     extend_existing=True
                     )

MySecondModelTable = Table('mysecondmodels',
                           Column('id', Integer, primary_key=True),
                           Column('name', String(50)),
                           extend_existing=True,
                           info={'bind_key': 'mysql2'}
                           )


class MyModel(Model):
    """
    My Model
    """

    __table__ = MyModelTable

    def __init__(self, name):
        self.name = name


class MySecondModel(Model):
    """
    My Second Model
    """

    __table__ = MySecondModelTable

    def __init__(self, name):
        self.name = name


mapper(MyModel, MyModelTable)
mapper(MySecondModel, MySecondModelTable)
