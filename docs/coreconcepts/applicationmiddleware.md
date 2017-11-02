
# Application Middleware

Application Middleware is the proper way of layering your application.
Middleware can be used to add functionality to your application when
processing calls. The middleware gets called each time your application
is called.

## Define

Define your Application Middleware like so:
```python
from edmunds.foundation.applicationmiddleware import ApplicationMiddleware

class MyApplicationMiddleware(ApplicationMiddleware):
    """
    My Application Middleware
    """

    def handle(self, environment, start_response):
        """
        Handle the middleware
        :param environment:     The environment
        :type  environment:     Environment
        :param start_response:  The response
        :type  start_response:  Response
        """

        return super(MyApplicationMiddleware, self).handle(environment, start_response)
```

> Note: Application Middleware is the equivalent of the Flask Middleware using
wsgi_app-wrappers.


## Register

Register the Application Middleware once it needs to be loaded:
```python
from app.Foundation.MyApplicationMiddleware import MyApplicationMiddleware

app.middleware(MyApplicationMiddleware)
```
This way the handle-function of your Application Middleware is called.

> Note: Application Middleware can only be registered once.
