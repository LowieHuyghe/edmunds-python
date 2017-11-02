
# Translations

Translations and user experience go hand in hand like cookies and milk.
That's why Edmunds helps you out when it comes down to translating.

Sentences used in translations are dynamically constructed using parameters,
the plural-function and the gender-function.

* Parameters will be filled in as given and formatted when in the correct format (integer and float as number,
time and date and datetime as time. string will remain untouched.).
* The plural-function lets you print plurals which is different depending on
the locale (see babel.messages.plurals).
* The gender-function helps you print out possessive pronouns, or gender-bound
words or verbs depending on the context.


## Configuration

```python
from edmunds.localization.translations.drivers.configtranslator import ConfigTranslator

APP = {
    'localization': {
        'enabled': True,
        
        'locale': {
            'fallback': 'en',
            'supported': ['en', 'en_US', 'nl'],
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
                    'beautiful': 'This is a beautiful translation in en. Is it not, {name}?',
                    'smashing': 'A smashing sentence in en!',
                    'liking': 'I\'m taking a liking to --gender:{user}__him__her--...',
                },
                'en_US': {
                    'beautiful': 'This is a beautiful translation in en_US. Is it not, {name}?',
                    'smashing': 'A smashing sentence in en_US!',
                },
                'nl': {
                    'beautiful': 'Dit is een prachtige vertaling in nl. Nietwaar, {name}?',
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

* Params are defined as follows: `{paramname}`.
* Functions are defined as follows: `--functionname:{argname1},{argname2}__option 1__option 2--`
  - Plural-function: `--plural:{count}__{count} apple__{count} apples--`
  - Gender-function: `--gender:{user}__his apple__her apple--`

> Note: Parameters used as arguments of a function will not be formatted.

Examples:

* `This is a beautiful translation in en. Is it not, {name}?`
* `A smashing sentence in en!`
* `I'm taking a liking to --gender:{user}__him__her--...`


### Using translations

```python
from edmunds.http.controller import Controller

class MyController(Controller):
    def login(self):
        
        # Usage through the visitor object
        # Note: Visitor will use the first location and translation driver!
        
        sentence = self.visitor.localizator.translate('beautiful', {'name': 'Steve'})
        # locale 'en':      This is a beautiful translation in en. Is it not, Steve?
        # locale 'en_US':   This is a beautiful translation in en_US. Is it not, Steve?
        # locale 'nl':      Dit is een prachtige vertaling in nl. Nietwaar, Steve?
        
        sentence = self.visitor.localizator.translate('smashing')
        # locale 'en':      A smashing sentence in en!
        # locale 'en_US':   A smashing sentence in en_US!
        # locale 'nl':      A smashing sentence in en!      (using fallback en)
        
        
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
        
        sentence = localizator.translate('liking', {'user': 'F'})
        # locale 'en':      I'm taking a liking to her...
        # locale 'en_US':   I'm taking a liking to her...   (using en without region US)
        # locale 'nl':      I'm taking a liking to her...   (using fallback en)
        
        # ...
```
