
# Translations

Translations and user experience go hand in hand like cookies and milk.
That's why Edmunds helps you out when it comes down to translating.


## Configuration

```python
from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator

APP = {
    'localization': {
        'enabled': True,
        
        'locale': {
            'fallback': 'en',
            'supported': ['en_US', 'en', 'nl_BE', 'nl'],
        },
        'time_zone_fallback': 'Europe/Brussels',
        
        'translations': {
            'enabled': True,
            'instances': [
                   {
                    'name': 'configtranslator',
                    'driver': ConfigTranslator,
                },
            ],
            
            # The ConfigTranslator uses configuration to fetch the translations.
            #   To keep the config clean, try splitting the translations up in
            #   different config files.
            #   More on how to format these sentences below.
            'strings': {
                'en': {
                    'beautiful': 'This is a beautiful translation. Is it not, {name}?',
                    'smashing': 'A smashing sentence!',
                    'liking': 'I\' taking a liking to --gender:{user}__him__her--...',
                },
                'nl': {
                    'beautiful': 'Dit is een prachtige vertaling. Nietwaar, {name}?',
                },
            },
        },
    },
}
```

The available drivers are:
- **ConfigTranslator**: Fetches translations from config


## Usage

### Constructing sentences



### Using translations

```python
from edmunds.http.controller import Controller

class MyController(Controller):
    def login(self):
        
        # Usage through the visitor object
        
        country_iso = self._visitor.location.country.iso_code
        city_name = self._visitor.location.city.name
        # ...
        
        
        # Usage through the app/manager
        
        localization_manager = self._app.localization()
        location_driver = localization_manager.location()
        location = location_driver.insights(self._request.remote_addr)
        translator = localization_manager.translator(location)
        
        sentence = translator.get('smashing')
        sentence = translator.get('beautiful', {'name': 'Steve'})
        sentence = translator.get('liking', {'user': 'F'})
        # ...
```
