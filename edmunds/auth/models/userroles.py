
from edmunds.database.model import Table, Column, Integer, ForeignKey


UserRolesTable = Table(
    'user_roles',
    Column('user_id', Integer, ForeignKey('users.id')),
    Column('role_id', Integer, ForeignKey('role.id')),

    #  info={'bind_key': 'users_database'},
)
