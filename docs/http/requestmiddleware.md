
# Request Middleware

Request Middleware is the proper way of layering your request-handling.
It lets you add functionality before and after processing the request.


## Define

Define your Request Middleware like so:
```python
from edmunds.http.requestmiddleware import RequestMiddleware

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


## Register

Register the Request Middleware in `routes.py` as an option of the routes:
```python
from app.http.myrequestmiddleware import MyRequestMiddleware
from app.http.myotherrequestmiddleware import MyOtherRequestMiddleware
from app.http.mycontroller import MyController

app.route('/', uses=(MyController, 'get_index')) \
    .middleware(MyRequestMiddleware)

app.route('/route2', uses=(MyController, 'get_route2')) \
    .middleware(MyOtherRequestMiddleware, 'arg1', 'arg2', kwarg1='value')

@app.route('/route3', middleware=[MyRequestMiddleware])
def old_skool_route():
    return "Hello World!"

@app.route('/route4', middleware=[(MyOtherRequestMiddleware, 'arg1', 'arg2')])
def second_old_skool_route():
    return "Hello World!"
```
This way the before- and after-function of your Request Middleware is called.

> Note: The order in which the middleware is given, will also be the order in which they are called.
