
# Formatting

Formatting of values is mandatory when localizing your application. One thousand
three hundred forty five point twenty two is not written the same everywhere:
1,345.22 ; 1.345,22 ; 1345,22 ; ... Also time should be formatted to the users
time-zone.


## Configuration

```python
APP = {
    'localization': {
        'enabled': True,
        
        'locale': {
            'fallback': 'en',
            'supported': ['en', 'en_US', 'nl'],
        },
        'time_zone_fallback': 'Europe/Brussels',
    },
}
```


## Usage

```python
from edmunds.http.controller import Controller
from datetime import time, date, datetime
from edmunds.localization.localization.models.time import Time

class MyController(Controller):
    def login(self):
        
        # Usage through the visitor object
        
        formatted_integer = self.visitor.localizator.number.number(1345)
        formatted_decimal = self.visitor.localizator.number.number(1345.22)
        formatted_currency = self.visitor.localizator.number.currency(1345.22, 'EUR')
        formatted_percentage = self.visitor.localizator.number.percent(0.35)
        formatted_scientific = self.visitor.localizator.number.scientific(232339)
        formatted_rtl = self.visitor.localizator.rtl
        # ...
        
        
        # Usage through the app/manager
        
        # Localization manager
        localization_manager = self.app.localization()
        # Location
        location_driver = localization_manager.location()
        location = location_driver.insights(self.request.remote_addr)
        # Translator
        translator = localization_manager.translator()
        # Localizator
        localizator = localization_manager.localizator(location, translator)
        
        formatted_time = localizator.time.time(time(22, 26, 12))
        formatted_time = localizator.time.time(time(22, 26, 12), format=Time.LONG)
        formatted_date = localizator.time.date(date(2017, 9, 18))
        formatted_date = localizator.time.date(date(2017, 9, 18), format=Time.SHORT)
        formatted_date = localizator.time.date(date(2017, 9, 18), format=Time.FULL)
        formatted_datetime = localizator.time.datetime(datetime(2017, 9, 18, 22, 26, 12))
        # ...
```
