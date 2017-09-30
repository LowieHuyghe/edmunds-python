
from edmunds.database.databasemanager import DatabaseManager
from sqlalchemy import UniqueConstraint as SqlAlchemyUniqueConstraint, CheckConstraint as SqlAlchemyCheckConstraint, \
    ForeignKeyConstraint as SqlAlchemyForeignKeyConstraint, PrimaryKeyConstraint as SqlAlchemyPrimaryKeyConstraint, \
    Index as SqlAlchemyIndex


db = DatabaseManager.get_sql_alchemy_instance()
Table = db.Table
Column = db.Column
ForeignKey = db.ForeignKey

BigInteger = db.BigInteger
Boolean = db.Boolean
Date = db.Date
DateTime = db.DateTime
Enum = db.Enum
Float = db.Float
Integer = db.Integer
Interval = db.Interval
LargeBinary = db.LargeBinary
Numeric = db.Numeric
PickleType = db.PickleType
SmallInteger = db.SmallInteger
String = db.String
Text = db.Text
Time = db.Time
Unicode = db.Unicode
UnicodeText = db.UnicodeText

UniqueConstraint = SqlAlchemyUniqueConstraint
CheckConstraint = SqlAlchemyCheckConstraint
ForeignKeyConstraint = SqlAlchemyForeignKeyConstraint
PrimaryKeyConstraint = SqlAlchemyPrimaryKeyConstraint
Index = SqlAlchemyIndex
