
# Token Authentication

Token authentication can be set up using the [introduction](./introduction.md) and
some help from [this post](http://mandarvaze.github.io/2015/01/token-auth-with-flask-security.html).

Add the `TokenAuthMiddleware`-middleware to your routes to protect them. This middleware
is a wrapper for the
[`auth_token_required`-decorator](https://pythonhosted.org/Flask-Security/api.html#flask_security.decorators.auth_token_required):
```python
from edmunds.auth.middleware.tokenauthmiddleware import TokenAuthMiddleware
from app.http.controllers.mycontroller import MyController

app.route('/loggedin', uses=(MyController, 'get_logged_in')) \
    .middleware(TokenAuthMiddleware)
```


## TL;DR

The following request will give you an authentication token:

* Post-request to `/login`
  - Data:
    - `email`
    - `password`
  - Headers:
    - `content-type`: `application/json`

Add the token to future requests using either:

- Request-data: `auth_token`
- Header: `Authentication-Token`
