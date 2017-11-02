
# Routing

Routing the request has slightly been modified with some inspiration from
Laravel. The routing of Flask applies, but a minor change has been made
so controllers can be used.


## Basic routing

```python
app.route('/', uses=(MyController, 'get_index'))
```
This will route the request to `get_index` in `MyController`:
```python
from edmunds.http.controller import Controller

class MyController(Controller):
    """
    My Controller
    """

    def initialize(self, **params):
        """
        Initialize the controller
        :param params:      The parameters in the url
        :type  params:      dict
        """
        super(MyController, self).initialize()

    def get_index(self):
        """
        Get the index-page
        """
        return 'Hello World!'

```
As you can see there is a method called `initialize`. This method is
responsible for initializing the controller.

Constructing and initializing the controller is done in this order:

1. Construct the controller *(`__init__`)*
2. Initialize the controller *(`initialize`)*
3. Call the method and return the response *(`get_index`)*


## Dynamic routing

Adding variable parts to a url is done by marking them with special sections:
```python
app.route('/user/<username>', uses=(MyController, 'get_user'))
app.route('/post/<int:post_id>', uses=(MyController, 'get_post')) # Using converters
```
Other possible converters:

- **string**: accepts any text without a slash (the default)
- **int**: accepts integers
- **float**: like int but for floating point values
- **path**: like the default but also accepts slashes
- **any**: matches one of the items provided
- **uuid**: accepts UUID strings

These parameters are catched in the controller as followed:
```python
def get_user(self, username = None):
        return 'User: %s' % username

def get_post(self, post_id = None):
        return 'Post with id: %d' % post_id
```

As seen previously, the parameters are also passed to the `initialize`-method.


## HTTP methods

HTTP knows different methods which can be defined in the routes. By default the route will listen to `GET`-requests.
```python
app.route('/login', uses=(LoginController, 'get_login'), methods = ['GET'])
app.route('/login', uses=(LoginController, 'post_login'), methods = ['POST'])
```
All supported methods:

- `GET`
- `HEAD`
- `POST`
- `PUT`
- `DELETE`
- `OPTIONS`