
# Database

Database usage is built in in Edmunds and uses
[Flask-SQLAlchemy](http://flask-sqlalchemy.pocoo.org/).

## Settings

You can set your database preferences in the settings:
```python
from edmunds.database.drivers.mysql import MySql
from edmunds.database.drivers.postgresql import PostgreSql
from edmunds.database.drivers.sqlite import Sqlite

APP = {
    'database':
    {
        'enabled': True,
        'instances':
        [
            {
                'name': 'mysql',
                'driver': MySql,
                'user': 'root',
                'pass': 'root',
                'host': 'localhost',
                'database': 'edmunds',
                # 'port': '3306', 	# Optional, default: '3306'
            },
            {
                'name': 'postgresql',
                'driver': PostgreSql,
                'user': 'root',
                'pass': 'root',
                'host': 'localhost',
                'database': 'edmunds',
                # 'port': '5432', 	# Optional, default: '5432'
            },
            {
                'name': 'sqlite',
                'driver': Sqlite,
                'file': 'sqlite.db',
                # 'storage': 'storage_name', 	# Optional, default storage used as default
            },
        ],
    },
}
```
The instances can be used for database, so you can have multiple at once.
The first one will be used by default.

The available drivers are:

- **MySql**: For MySQL databases.
- **PostgreSql**: For PostgreSQL databases.
- **SQLite**: For SQLite databases.

> More in detail: the default instance will be used as `SQLALCHEMY_DATABASE_URI`
> in SQLAlchemy. Other instances (not including the default instance) will
> be added to `SQLALCHEMY_BINDS`.


## Usage

When fetching an instance, you will receive a database-engine
(sqlalchemy.engine.base.Engine) for the specified database instance.
You can request one like so:
```python
# Fetch the default driver, or by name
engine = app.database_engine()
engine = app.database_engine(name='mysql')
```

Fetching a session can be done with `database_session`. You will receive a
Session-class (sqlalchemy.orm.scoping.scoped_session) for session-usage.
Sessions will be teared down when the request ends or when the app shuts
down (using `app.teardown_appcontext`).
```python
session = app.database_session()
session = app.database_session(name='mysql')

session.add(user)
session.commit()
```

Further usage of the database-engine and -session are described in the
SQLAlchemy documentation:

* [Flask-SQLAlchemy](http://flask-sqlalchemy.pocoo.org/)
* [SQLAlchemy - Working with Engines and Connections](http://docs.sqlalchemy.org/en/latest/core/connections.html)
* [SQLAlchemy - Session Basics](http://docs.sqlalchemy.org/en/latest/orm/session_basics.html)
