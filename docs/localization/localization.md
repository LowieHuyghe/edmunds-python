
# Localization

Localization is used to tailor an experience for the user that is totally
customized to his/her language, unit-system, currency,...


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
            'fallback': 'en_US',
            'supported': ['en_US', 'en', 'nl_BE', 'nl'],
        },
        
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
The location service only works with the first defined instance.

The available drivers are:
- **MaxMindCityDatabase**: Using MaxMind City Database
- **MaxMindEnterpriseDatabase**: Using MaxMind Enterprise Database
- **MaxMindWebService**: Using MaxMind Web Service
- **GoogleAppEngine**: Based on specific Google App Engine headers
