
# Cookies

Cookies are available for usage when in request context.


## Usage

Cookies can be accessed inside controller and work like dictionaries.
You can fetch given cookies using keys, and set them to
the response by assigning values to `self._cookies`.

```python
from edmunds.http.controller import Controller

class MyController(Controller):
    def login(self):
        prev_user_id = self._cookies['user_id']
        prev_user_id = self._cookies.pop('user_id', None)
        del self._cookies['user_id']
        self._cookies['user_id'] = self._input['user_id']
```
