
# Input and Validation

For an explanation on input and validation, you are on the right address.


## Input

Querystring-data, form-data and file-data are all combined in the Input-class.
The class takes the current request in its constructor and processed
request.args, request.form and request.files. All data can be accessed like
you would access them separately.

```python
from edmunds.http.input import Input
from edmunds.globals import request

input = Input(request)
username = input['username']
password = input['password']
city = input.get('city', 'unknown')
```

> Note: for more on the request-data see the [docs](http://flask.pocoo.org/docs/0.12/quickstart/#the-request-object)

### Usage in Controllers

Controllers are by default equipped with an instance of the input object.
You can use it to access the submitted data.

```python
from edmunds.http.controller import Controller

class MyController(Controller):
    def login(self):
        username = self.input['username']
        password = self.input['password']
        city = self.input.get('city', 'unknown')
```


## Validation

Edmunds has a validator which extends from `wtforms.Form`. You can use it
like you would use WTForms. Validator has one extra attribute: `validates`
which keeps the state of the last call to `validates()`.

> Note: for more on WTForms see the [docs](http://flask.pocoo.org/docs/0.12/patterns/wtforms)

### Usage with Input

Input has the Validator builtin. You can use it by passing your Validator-class
to the `validate`-function.

```python
from edmunds.validation.validator import Validator
from wtforms import StringField, PasswordField, validators
from edmunds.http.controller import Controller

class LoginValidator(Validator):
    email = StringField('Email Address', [validators.Length(min=6, max=35)])
    password = PasswordField('Password', [validators.DataRequired()])

class MyController(Controller):
    def login(self):
        validator = self.input.validate(LoginValidator)
        if validator.validates:
            pass
```
