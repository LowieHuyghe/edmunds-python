
# ORM

Object-Relational Mapping is implemented using
[Flask-SQLAlchemy](http://flask-sqlalchemy.pocoo.org/).

Edmunds uses the 'Classical Mappings'-way of SQLAlchemy. Here you define
your tables and model separately and map them together afterwards. This
gives you more freedom.


## Defining and mapping models

First off you need to define your models. After defining the model-class
you can map it to the table using the `mapper`:

```python
from app.database.tables.userstable import UsersTable
from app.database.tables.tagstable import TagsTable
from app.database.tables.userstagstable import UserTagsTable
from edmunds.database.model import Model, mapper, relationship

# app/database/models/user.py
from app.database.models.tag import Tag

class User(Model):
    __table__ = UsersTable

    def __init__(self, name):
        self.name = name

    def __repr__(self):
        return '<User name="%s" id="%s"/>' % (self.name, self.id)

mapper(User, UsersTable, properties={
    'tags': relationship(Tag, backref='users', secondary=UserTagsTable)
})

# app/database/models/tag.py
class Tag(Model):
    __table__ = TagsTable

    def __init__(self, name):
        self.name = name

mapper(Tag, TagsTable)
```


## Insert, Update, Delete

The actions work the same way as Flask-SQLAlchemy does:
```python
session = User.session()
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

Querying works the same way as defined in Flask-SQLAlchemy, with the small
difference that the `query`-class-property is now a class-method. This is
so you can query on different database-instance if required. By default
the database-instance defined in `bind_key` of the `__table__`-table is
used. The second fallback is the default database-instance.

```python
users = User.query().all()  # Would default to UsersTable.info['bind_key'] or default instance
peter = User.query(name='mysql').filter_by(name='peter').first()  # Uses database-instance with name 'mysql'
```

Further documentation on querying:
* [Flask-SQLAlchemy Querying Records](http://flask-sqlalchemy.pocoo.org/2.1/queries/#querying-records)
* [SQLAlchemy Querying](http://docs.sqlalchemy.org/en/latest/orm/tutorial.html#querying)

> Tip: `ModelClass.session()` works the same way as `ModelClass.query()`
> does, but returns the result of `app.database_session()` with the given
> database-instance by name.
