
# Session Authentication

Session authentication can be set up using the [introduction](./introduction.md).

Add the `SessionAuthMiddleware`-middleware to your routes to protect them. This middleware
is a wrapper for the
[`login_required`-decorator](https://pythonhosted.org/Flask-Security/api.html#flask_security.decorators.login_required):
```python
from edmunds.auth.middleware.sessionauthmiddleware import SessionAuthMiddleware
from app.http.controllers.mycontroller import MyController

app.route('/loggedin', uses=(MyController, 'get_logged_in')) \
    .middleware(SessionAuthMiddleware)
```
