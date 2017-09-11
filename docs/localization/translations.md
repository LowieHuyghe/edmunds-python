
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
            'fallback': 'en_US',
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
            'strings': {
                'en': {
                    'beautiful': 'This is a beautiful translation. Is it not, {name}?',
                    'smashing': 'A smashing sentence!',
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
