
from edmunds.database.table import Table, Column, Integer, String


RoleTable = Table(
    'role',
    Column('id', Integer, primary_key=True),
    Column('name', String(50), unique=True),
    Column('description', String(255)),

    extend_existing=True,  # To dodge: https://github.com/mitsuhiko/flask-sqlalchemy/issues/478
    #  info={'bind_key': 'users_database'},
)
