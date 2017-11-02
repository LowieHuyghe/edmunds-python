
# Request

Request is available for usage when in request context.


## Usage

The current request can be accessed inside the controller and get used
like the flask request.

```python
from edmunds.http.controller import Controller

class MyController(Controller):
    def login(self):
        user_ip = self.request.remote_addr
```

> Note: for more on the request see the [Flask documentation](http://flask.pocoo.org/docs/0.12/api/#flask.Request)
