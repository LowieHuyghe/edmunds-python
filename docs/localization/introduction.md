
# Localization

Localization is used to tailor an experience for the user that is totally
customized to his/her language, unit-system, currency,...

Accuracy in localization is achieved using different sources and factors:

* Locale is determined based on (first = highest priority):
  - Given locale (fixed locale set on client side. given as argument.)
  - Browser accept languages
  - User agent
  - Fallback locale set in config
* Location is determined on the client ip address using the given drivers.
* Time-zone is determined based on (first = highest priority):
  - Location
  - Fallback time-zone set in config

The locale will determine how values are formatted and which translations
are shown to the user. The locales are processed for usage and will comply
to the following rules:

* Locales specifying a region will also be processed without region
(nl_NL => nl_NL, nl)
* Only supported locales will be used.


## Configuration

This is an example configuration. See related localization-documentation for
more information.

```python
from edmunds.localization.location.drivers.maxmindcitydatabase import MaxMindCityDatabase
from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator

APP = {
    'localization': {
        'enabled': True,
        
        'locale': {
            'fallback': 'en',
            'supported': ['en', 'en_US', 'nl'],
        },
        'time_zone_fallback': 'Europe/Brussels',
        
        'location': {
            'enabled': True,
            'instances': [
                {
                    'name': 'maxmindcitydb',
                    'driver': MaxMindCityDatabase,
                    'database': 'maxmind_city_db.mmdb'
                },
            ],
        },
        
        'translations': {
            'enabled': True,
            'instances': [
                   {
                    'name': 'configtranslator',
                    'driver': ConfigTranslator,
                },
            ],
        },
    },
}
```

## Usage

The general localization will be shown here. Other usages will be shown in the
related localization-documentation.

> Note: Localization will be more accurate when the location of the user
> is available.

```python
from edmunds.http.controller import Controller
from datetime import time, date

class MyController(Controller):
    def login(self):
        
        # Usage through the visitor object
        
        time_str = self.visitor.localizator.time.time(time(14, 3, 2))
        date_str = self.visitor.localizator.time.date(date(1992, 6, 7))
        # ...
        cost = self.visitor.localizator.number.currency(4.56, 'EUR')
        number = self.visitor.localizator.number.number(3456.64)
        # ...
        is_rtl = self.visitor.localizator.rtl
        locale = self.visitor.localizator.locale
        # ...
        
        
        # Usage through the app/manager
        
        localization_manager = self.app.localization()
        location_driver = localization_manager.location()
        location = location_driver.insights(self.request.remote_addr)
        localizator_model = localization_manager.localizator(location)
        
        time_str = localizator_model.time.time(time(14, 3, 2))
        date_str = localizator_model.time.date(date(1992, 6, 7))
        # ...
        cost = localizator_model.number.currency(4.56, 'EUR')
        number = localizator_model.number.number(3456.64)
        # ...
        is_rtl = localizator_model.rtl
        locale = localizator_model.locale
        # ...
```
