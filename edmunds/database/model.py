
import sqlalchemy.orm
from edmunds.database.databasemanager import DatabaseManager
from sqlalchemy import UniqueConstraint as SqlAlchemyUniqueConstraint, CheckConstraint as SqlAlchemyCheckConstraint, \
    ForeignKeyConstraint as SqlAlchemyForeignKeyConstraint, PrimaryKeyConstraint as SqlAlchemyPrimaryKeyConstraint, \
    Index as SqlAlchemyIndex
from werkzeug.local import LocalProxy

db = LocalProxy(lambda: DatabaseManager.get_sql_alchemy_instance())
mapper = sqlalchemy.orm.mapper
relationship = sqlalchemy.orm.relationship
backref = sqlalchemy.orm.backref

UniqueConstraint = SqlAlchemyUniqueConstraint
CheckConstraint = SqlAlchemyCheckConstraint
ForeignKeyConstraint = SqlAlchemyForeignKeyConstraint
PrimaryKeyConstraint = SqlAlchemyPrimaryKeyConstraint
Index = SqlAlchemyIndex
