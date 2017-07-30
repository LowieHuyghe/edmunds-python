
# Visitor

The visitor class is used to get information regarding the client.
Browser-related-info, ip-based-location, localization,...

To make location and localization work, you will need to setup some
configuration. See [localization](../localization/localization.md).


## Usage

The visitor object will be available in controllers when handling requests.

```python
from edmunds.http.controller import Controller
from datetime import time, date

class MyController(Controller):
    def login(self):
    
        # Client info
        client_os = self._visitor.client.os
        client_browser = self._visitor.client.browser
        client_device = self._visitor.client.device
        client_is_mobile = self._visitor.client.is_mobile
        client_is_bot = self._visitor.client.is_bot
        # ...
        
        # Ip-based-location
        country_iso = self._visitor.location.country.iso_code
        city_name = self._visitor.location.city.name
        # ...
        
        # Localization
        time_str = self._visitor.localization.time.time(time(14, 3, 2))
        date_str = self._visitor.localization.time.date(date(1992, 6, 7))
        # ...
        cost = self._visitor.localization.number.currency(4.56, 'EUR')
        number = self._visitor.localization.number.number(3456.64)
        # ...
        is_rtl = self._visitor.localization.rtl
        locale = self._visitor.localization.locale
        # ...
```
