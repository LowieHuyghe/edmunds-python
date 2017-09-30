
from edmunds.database.table import Table, Column, Integer, ForeignKey


UserRolesTable = Table(
    'user_roles',
    Column('user_id', Integer, ForeignKey('users.id')),
    Column('role_id', Integer, ForeignKey('role.id')),

    extend_existing=True,  # To dodge: https://github.com/mitsuhiko/flask-sqlalchemy/issues/478
    #  info={'bind_key': 'users_database'},
)
