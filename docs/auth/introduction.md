
# Authentication

Authentication is managed using [Flask-Security](https://pythonhosted.org/Flask-Security/index.html).

Edmunds already implements some of the features of
Flask-Security to get you started.


## Settings

You can set your authentication preferences in the settings:
```python
from flask_security import SQLAlchemyUserDatastore
from app.database.models.user import User
from app.database.models.role import Role

SECURITY_PASSWORD_HASH = 'sha512_crypt'
SECURITY_TRACKABLE = True
APP = {
    'auth':
    {
        'enabled': True,
        'instances':
        [
            {
                'name': 'authsqlalchemy',
                'driver': SQLAlchemyUserDatastore,
                'models': {
                    'user': User,
                    'role': Role,
                },
            },
        ],
    },
}
```
You can have multiple user datastores by defining them
as instances. Each instance will have their own models.

The available drivers are:

- **SQLAlchemyUserDatastore**: User management using SQLAlchemy


## User- and Role-Model

Both the user- and role-model can use the predefined
mixins of Edmunds. The mixins already define the default
columns for your database-structure.

```python
# app/database/role.py

from edmunds.auth.models.rolemixin import RoleMixin
from edmunds.database.db import db

class Role(db.Model, RoleMixin):
    # __tablename__ = 'role'
    # __bind_key__ = 'users'
    pass

# app/database/user.py

from edmunds.database.db import relationship, backref
from edmunds.auth.models.usermixin import UserMixin
from app.database.models.role import Role
from app.database.models.userroles import UserRolesTable
from edmunds.database.db import db

class User(db.Model, UserMixin):
    # __tablename__ = 'user'
    # __bind_key__ = 'users_database'
    roles = relationship(Role, backref=backref('users', lazy='dynamic'), secondary=UserRolesTable)
```


## Usage

Fetch the `flask_security.Security` instance for your config-defined instance: 
```python
security_instance = app.auth_security()
security_instance = app.auth_security(name='authsqlalchemy')
```

Fetch the `flask_security.datastore.UserDatastore` instance for your
config-defined instance: 
```python
userdatastore_instance = app.auth_userdatastore()
userdatastore_instance = app.auth_userdatastore(name='authsqlalchemy')

userdatastore_instance = app.auth_security(name='authsqlalchemy').datastore
```
