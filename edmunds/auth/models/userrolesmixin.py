
from edmunds.database.model import Column, Integer, ForeignKey


UserRolesMixin = [
    Column('user_id', Integer, ForeignKey('user.id')),
    Column('role_id', Integer, ForeignKey('role.id')),
]
