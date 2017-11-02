
# ORM

Object-Relational Mapping is implemented using
[Flask-SQLAlchemy](http://flask-sqlalchemy.pocoo.org/).


## Defining and mapping models

First off you need to define your models as described by the
[SQL-Alchemy documentation](http://flask-sqlalchemy.pocoo.org/):

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


## Insert, Update, Delete

The actions work the same way as Flask-SQLAlchemy does:
```python
session = app.database_session()
peter = User(name='peter')

# Inserting
session.add(peter)
session.commit()
print peter.id

# Updating
peter.name = 'peter verkest'
session.commit()

# Deleting
session.delete(peter)
session.commit()
```

Further documentation:

* [Flask-SQLAlchemy Select, Insert, Delete](http://flask-sqlalchemy.pocoo.org/2.1/queries)
* [SQLAlchemy Adding and Updating Objects](http://docs.sqlalchemy.org/en/latest/orm/tutorial.html#adding-and-updating-objects)

> Note: These actions are handy, but not efficient when running in bulk.
> Take a look at this [StackOverflow-question](https://stackoverflow.com/questions/270879/efficiently-updating-database-using-sqlalchemy-orm).


## Querying

Querying works as defined in Flask-SQLAlchemy.

```python
users = User.query.all()
peter = User.query.filter_by(name='peter').first()
```

Further documentation on querying:

* [Flask-SQLAlchemy Querying Records](http://flask-sqlalchemy.pocoo.org/2.1/queries/#querying-records)
* [SQLAlchemy Querying](http://docs.sqlalchemy.org/en/latest/orm/tutorial.html#querying)
