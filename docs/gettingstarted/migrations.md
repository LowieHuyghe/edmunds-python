
# Migrations

Database migrations are built in in Edmunds using Flask-Migrate.


## Usage

Edmunds has already integrated the db-command in the application's manager.
You can use it as described in the Flask-Migrate-documentation:
```bash
python manage.py db --help

# Init for your project:
python manage.py db init --multidb
# Migrate the changes:
python manage.py db migrate
# Upgrade the databases:
python manage.py db upgrade
```

Usage of Flask-Migrate documentation:
* [Flask-Migrate](https://flask-migrate.readthedocs.io)

### Tables

Tables of SQLAlchemy are used by Flask-Migrate to describe your database
structure. Add your table to `app.database.tables` so they are picked up
by the migration-service:
```python
# app/database/tables/userstable.py
from edmunds.database.table import Table, Column, Integer, String
UsersTable = Table('users',
                   Column('id', Integer, primary_key=True),
                   Column('name', String(50)),
                   # info={'bind_key': 'users_database'},
                   )
                   
# app/database/tables/tagstable.py
from edmunds.database.table import Table, Column, Integer, String
TagsTable = Table('tags',
                  Column('id', Integer, primary_key=True),
                  Column('name', String(50), unique=True)
                  # info={'bind_key': 'users_database'}
                  )

# app/database/tables/usertagstable.py
from edmunds.database.table import Table, Column, Integer, ForeignKey
UserTagsTable = Table('user_tags',
                      Column('user_id', Integer, ForeignKey('tags.id'), primary_key=True),
                      Column('tag_id', Integer, ForeignKey('users.id'), primary_key=True)
                      # info={'bind_key': 'users_database'}
                      )
```

Further documentation on how to declare your tables can be found here:
* [SQLAlchemy Schema Definition Language](http://docs.sqlalchemy.org/en/latest/core/schema.html)
* [Multiple Databases (binds)](http://flask-sqlalchemy.pocoo.org/2.2/binds/)

> Note: If you want to customize the package where the migrate-service looks
> for tables, you can override it in your config:
> ```python
> APP = {
>     'database': {
>         # ...
>         'tables': {
>             'my/location/to/the/tables',
>             'second/location/to/other/tables',
>         },
>     },
> }
> ```
> The described paths are relative to the root-folder of your application.
