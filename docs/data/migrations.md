
# Migrations

Database migrations are built in in Edmunds using [Flask-Migrate](https://flask-migrate.readthedocs.io).


## Setup

In order to work with migrations, a database will have to be configured.
Migrations use models and tables to track the changes in your database.
These models and tables are mostly not imported by default when they are
defined separately. So that is why it is advised to keep a
list of models and tables in your configuration. This way all models and
tables are loaded when doing migrations.

```python
from edmunds.database.drivers.sqlite import Sqlite
from app.database.models.user import User
from app.database.models.tag import Tag
from app.database.models.usertags import UserTags

APP = {
    'database':
    {
        'enabled': True,
        'instances':
        [
            {
                'name': 'sqlite',
                'driver': Sqlite,
                'file': '/database/sqlite.db'
            },
        ],
        'modelsandtables':
        [
            User,
            Tag,
            UserTags,
        ],
    },
}
```

By default the migrations service provider is not loaded as it requires
the database-instances to be loaded. You will have to register the
service provider during bootstrap. This gives you more freedom in case
you want to extend the databasemanager.

```python
from edmunds.database.providers.migrateserviceprovider import MigrateServiceProvider

app.register(MigrateServiceProvider)
```


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
database structure:
```python
# app/database/models/user.py
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
