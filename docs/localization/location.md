
# Location

This documentation describes how the location of a user can be fetched by ip.


## Configuration

```python
from edmunds.localization.location.drivers.maxmindcitydatabase import MaxMindCityDatabase
from edmunds.localization.location.drivers.maxmindenterprisedatabase import MaxMindEnterpriseDatabase
from edmunds.localization.location.drivers.maxmindwebservice import MaxMindWebService
from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine

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
                    'name': 'gae',
                    'driver': GoogleAppEngine,
                },
                {
                    'name': 'maxmindcitydb',
                    'driver': MaxMindCityDatabase,
                    'database': 'maxmind_city_db.mmdb'
                },
                {
                    'name': 'maxmindenterprisedb',
                    'driver': MaxMindEnterpriseDatabase,
                    'database': 'maxmind_enterprise_db.mmdb'
                },
                {
                    'name': 'maxmindweb',
                    'driver': MaxMindWebService,
                    'user_id': '1',
                    'license_key': 'license_key'
                },
            ],
        },
    },
}
```

The available drivers are:

- **MaxMindCityDatabase**: Using MaxMind City Database
- **MaxMindEnterpriseDatabase**: Using MaxMind Enterprise Database
- **MaxMindWebService**: Using MaxMind Web Service
- **GoogleAppEngine**: Based on specific Google App Engine headers

## Usage

```python
from edmunds.http.controller import Controller

class MyController(Controller):
    def login(self):
        
        # Usage through the visitor object
        # Note: Visitor will use the first location driver!
        
        country_iso = self.visitor.location.country.iso_code
        city_name = self.visitor.location.city.name
        # ...
        
        
        # Usage through the app/manager
        
        localization_manager = self.app.localization()
        location_driver = localization_manager.location()
        location = location_driver.insights(self.request.remote_addr)
        
        country_iso = location.country.iso_code
        city_name = location.city.name
        # ...
```
