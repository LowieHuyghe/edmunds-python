
from edmunds.database.databasemanager import DatabaseManager


db = DatabaseManager.get_sql_alchemy_instance()
Model = db.Model
