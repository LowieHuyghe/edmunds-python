
from edmunds.database.table import Table, Column, Integer, String


RoleTable = Table(
    'role',
    Column('id', Integer, primary_key=True),
    Column('name', String(50), unique=True),
    Column('description', String(255)),

    #  info={'bind_key': 'users_database'},
)
