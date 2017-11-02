
# Visitor

The visitor class is used to get information regarding the client.
Browser-related-info, ip-based-location, localization,...

To make location and localization work, you will need to setup some
configuration. See [localization](../localization/introduction.md).


## Usage

The visitor object will be available in controllers when handling requests.

```python
from edmunds.http.controller import Controller
from datetime import time, date

class MyController(Controller):
    def login(self):
    
        # Client info
        client_os = self.visitor.client.os
        client_browser = self.visitor.client.browser
        client_device = self.visitor.client.device
        client_is_mobile = self.visitor.client.is_mobile
        client_is_bot = self.visitor.client.is_bot
        # ...
        
        # Ip-based-location
        country_iso = self.visitor.location.country.iso_code
        city_name = self.visitor.location.city.name
        # ...
        
        # Localization
        time_str = self.visitor.localization.time.time(time(14, 3, 2))
        date_str = self.visitor.localization.time.date(date(1992, 6, 7))
        # ...
        cost = self.visitor.localization.number.currency(4.56, 'EUR')
        number = self.visitor.localization.number.number(3456.64)
        # ...
        is_rtl = self.visitor.localization.rtl
        locale = self.visitor.localization.locale
        # ...
```
