
# Roles

The `RolesAcceptedMiddleware`-middleware and the `RolesRequiredMiddleware`-middleware
are two wrapper for respectively the
[`roles_accepted`-decorator](https://pythonhosted.org/Flask-Security/api.html#flask_security.decorators.roles_accepted)
and the
[`roles_required`-decorator](https://pythonhosted.org/Flask-Security/api.html#flask_security.decorators.roles_required).

They are slightly modified so they don't redirect when the requirements are not met.
They will just abort with the Forbidden-statuscode (403).

Add them to your routes to protect them:
```python
from edmunds.auth.middleware.rolesacceptedmiddleware import RolesAcceptedMiddleware
from edmunds.auth.middleware.rolesrequiredmiddleware import RolesRequiredMiddleware
from app.http.controllers.mycontroller import MyController

app.route('/acceptsroles', uses=(MyController, 'acceptsroles')) \
    .middleware(RolesAcceptedMiddleware, 'role1', 'role2')
app.route('/requiresroles', uses=(MyController, 'requiresroles')) \
    .middleware(RolesRequiredMiddleware, 'role2', 'role3')
```


## Methods

Read the [`UserDataStore`](https://pythonhosted.org/Flask-Security/api.html#flask_security.datastore.UserDatastore)-documentation
to see how roles are added and assigned to users.
