
# Request Middleware

Request Middleware is the proper way of layering your request-handling. It lets you add functionality before and after processing the request.


## Request Middleware

### Define

Define your Request Middleware like so:
```python
from Edmunds.Http.RequestMiddleware import RequestMiddleware

class MyRequestMiddleware(RequestMiddleware):
    """
    My Request Middleware
    """

    def before(self):
        """
        Handle before the request
        """

        return super(MyRequestMiddleware, self).before()

    def after(self, response):
        """
        Handle after the request
        :param response:    The request response
        :type  response:    flask.Response
        :return:            The request response
        :rtype:             flask.Response
        """

        return super(MyRequestMiddleware, self).after(response)
```

> Note: The before- and after-function work respectively like the @app.before_request and @app.after_request of Flask.

### Register

Register the Request Middleware in `routes.py` as an option of the routes:
```python
from app.Http.MyRequestMiddleware import MyRequestMiddleware
from app.Http.MyController import MyController

app.route('/', middleware = [ MyRequestMiddleware ], uses = (MyController, 'get_index'))
```
This way the before- and after-function of your Request Middleware is called.

> Note: The order in which the middleware is given, will also be the order in which they are called.