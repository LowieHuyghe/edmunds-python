
from edmunds.database.table import Table, Column, Integer, String, Boolean, DateTime


UserTable = Table(
    'user',
    Column('id', Integer, primary_key=True),
    Column('email', String(255), unique=True),
    Column('password', String(255)),
    Column('active', Boolean()),
    Column('confirmed_at', DateTime()),
    Column('last_login_at', DateTime()),
    Column('current_login_at', DateTime()),
    Column('last_login_ip', String(255)),
    Column('current_login_ip', String(255)),
    Column('login_count', Integer),

    extend_existing=True,  # To dodge: https://github.com/mitsuhiko/flask-sqlalchemy/issues/478
    #  info={'bind_key': 'users_database'},
)
