
# Service Providers

Service Providers are a way of keeping the application as light as possible.
This is done by separating your application in modules and only loading the
modules you need.

Also the service providers make sure your application is loaded completely
when starting up. So no loading needs to be done while processing requests.


## Define

Define your Service Provider like so:
```python
from edmunds.support.serviceprovider import ServiceProvider

class MyServiceProvider(ServiceProvider):
    """
    My Service Provider
    """

    def register(self):
        """
        Register the service provider
        """
        # Load in your module
        pass
```


## Register

Register the Service Provider once it needs to be loaded:
```python
from app.Providers.MyServiceProvider import MyServiceProvider

app.register(MyServiceProvider)
```
This way the register-function of your Service Provider provider is called.

> Note: A Service Provider can only be registered once.
