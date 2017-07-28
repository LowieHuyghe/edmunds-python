
# Response

Response is available for usage when in request context.

The class implemented extends the Response-wrapper from Flask. So no
changes there. But a ResponseHelper has been implemented for your comfort.
The helper makes it easier to construct your responses.

> Note: for more on the response (not the helper) see the
[Flask documentation](http://flask.pocoo.org/docs/0.12/api/#flask.Response)


## Usage

The current request can be accessed inside the controller and get used
like the flask request.

```python
from edmunds.http.controller import Controller

class MyController(Controller):
    def login(self):
        
        # Fix status for constructed responses
        self._response.status(404)
        
        # Assigns a value to a key
        # Will be used when constructing json-, or render-responses
        self._response.assign('name', 'Jon Snow')
        
        # Assigns headers to constructed responses
        self._response.header('XToken', 'mytoken')
        
        # Assigns cookie to constructed responses
        self._response.cookie('XToken', 'mytoken')
        
        # Returns a rendered template (using assigned values)
        result = self._response.render_template('mytemplate.html')
        
        # Returns raw response with given content
        return self._response.raw('My content')
        # Returns json response with assigned values
        return self._response.json()
        # Returns response with rendered template (using assigned values)
        return self._response.render('mytemplate.html')
        # Returns response to redirect browser
        return self._response.redirect('/newlocation')
        # Returns file response
        return self._response.file('filetodownload.txt')
        
```
