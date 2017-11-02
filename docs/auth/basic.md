
# Basic HTTP Authentication

Basic HTTP authentication can be set up using the [introduction](./introduction.md).

Add the `BasicAuthMiddleware`-middleware to your routes to protect them. This middleware
is a wrapper for the
[`http_auth_required`-decorator](https://pythonhosted.org/Flask-Security/api.html#flask_security.decorators.http_auth_required):
```python
from edmunds.auth.middleware.basicauthmiddleware import BasicAuthMiddleware
from app.http.controllers.mycontroller import MyController

app.route('/loggedin', uses=(MyController, 'get_logged_in')) \
    .middleware(BasicAuthMiddleware)
app.route('/loggedin', uses=(MyController, 'get_logged_in')) \
    .middleware(BasicAuthMiddleware, 'myrealm')
```
