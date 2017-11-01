
# Migrations

Database migrations are built in in Edmunds using [Flask-Migrate](https://flask-migrate.readthedocs.io).


## Usage

Edmunds has already integrated the db-command in the application's manager.
You can use it as described in the Flask-Migrate-documentation:
```bash
python manage.py db --help

# Init for your project:
python manage.py db init --multidb
# Migrate the changes:
python manage.py db migrate -m "Added some tables"
# Upgrade the databases:
python manage.py db upgrade
```

Usage of Flask-Migrate documentation:

* [Flask-Migrate](https://flask-migrate.readthedocs.io)

### Models & Tables

Models and tables of SQLAlchemy are used by Flask-Migrate to describe your
database structure. Add your models and tables to `app.database.models` so
they are picked up by the migration-service:
```python
# app/database/models/users.py
from edmunds.database.db import db
class User(db.Model):
    # __bind_key__ = 'users_database'
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(50))
                   
# app/database/models/tags.py
from edmunds.database.db import db
class Tag(db.Model):
    # __bind_key__ = 'users_database'
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(50), unique=True)

# app/database/models/usertags.py
from edmunds.database.db import db
UserTagsTable = db.Table(
    'user_tags',
    db.Column('user_id', db.Integer, db.ForeignKey('tags.id'), primary_key=True),
    db.Column('tag_id', db.Integer, db.ForeignKey('users.id'), primary_key=True),
    # info={'bind_key': 'users_database'}
)
```

Further documentation on how to declare your models and tables can be
found here:

* [SQLAlchemy Schema Definition Language](http://docs.sqlalchemy.org/en/latest/core/schema.html)
* [Multiple Databases (binds)](http://flask-sqlalchemy.pocoo.org/2.2/binds/)

> Note: If you want to customize the package where the migrate-service looks
> for models and tables, you can override it in your config:
> ```python
> APP = {
>     'database': {
>         # ...
>         'models': {
>             'my/location/to/the/models',
>             'second/location/to/other/tables',
>         },
>     },
> }
> ```
> The described paths are relative to the root-folder of your application.
