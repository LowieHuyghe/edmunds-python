
# Session

To activate session, enabled it by adding instances to your settings:

```python
from edmunds.session.drivers.sessioncookie import SessionCookie

APP = {
    'session':
    {
        'enabled': True,
        'instances':
        [
            {
                'name': 'sessioncookie',
                'driver': SessionCookie
            },
        ],
    },
}
```
The instances will all be used for session, so you can have multiple at once.

The available drivers are:

- **SessionCookie**: Sessions using cookies (see [docs](http://flask.pocoo.org/docs/0.11/quickstart/#sessions))


## Usage

Controller will have the first driver loaded for usage:

```python
from edmunds.http.controller import Controller

class MyController(Controller):
    def login(self):
        prev_username = self.session['username']
        prev_username = self.session.pop('username', None)
        del self.session['username']
        self.session['username'] = self._input['username']
```


## Usage outside controller

When in request-context, but not inside a controller, you can use the
application to get the driver-instance:

```python
session = app.session()
session = app.session('sessioncookie')

session['key'] = 'value'
print session['key']
del session['key']
```
