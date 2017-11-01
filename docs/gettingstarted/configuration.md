
# Configuration

Configuration is key to setting up an application.


## Handling configuration

Handling configuration is based upon the config-handling of Laravel. Original Flask-configuration-handling also still works.  
The keys used to fetch the values are mapped to the original keys specified in the files like so:
```
APP_ENV > app.env
SECRET_KEY > secret.key
DATABASE = {
    'mysql': {
        'user' > database.mysql.user
    }
}
```

Fetching a value:
```python
value = app.config('database.mysql.ip')
```

Updating values at runtime:
```python
app.config({
    'database.mysql.ip': 'localhost',
})
```


## Configuration-files

Configuring the application can be done in several places. There is the main configuration for the generic configuration of the application. And there is the environment specific configuration.

### Syntax

The default syntax in Flask configuration-files states that only key-value pares can be assigned and fetched:
```python
APP_AUTHOR_NAME = 'Edmunds'
APP_AUTHOR_AGE = 'Unknown'
```
With Edmunds it is possible to use the following syntax with the same result:
```python
APP = {
    'author': {
        'name': 'Edmunds',
        'age': 'Unknown',
    },
}
```
This allows for cleaner configuration-files. The Edmunds-syntax will be processed and converted to the Flask-syntax, which results in the same input as the Flask-example.


### Main configuration

The main configuration is located in the `config`-directory. There it is separated by namespace. Example structure:
```
config
  > app.py
  > database.py
  > session.py
```
The configuration-key starts with an ALL-CAPS key that defines the namespace. Further division is done with dictionaries. Example of a possible `session.py`:
```python
SESSION = {
    'ttl': 3600,
    'driver': 'memcache',
    'memcache': {
        'ip': '178.234.87.34'
    },
}
```


### Environment configuration

Some configuration is specific to the runtime environment. It depends on the machine it is running on, or on the current environment (development, production or testing). This configuration is not included in the git-repository, which makes it ideal for security sensitive configuration (like the app-secret).

The environment configuration is specified in the `.env.py`-files in the root of the project. An example of an `.env.py`-file:
```python
APP_ENV = 'development'
SECRET_KEY = 'aFBHjD8SHhqj71LEEmoxc8bLH4lzUTr'
```

The current environment given in the above example (`APP_ENV`) will try to load more specific configuration in `.env.development.py`. This allows you to use separate databases or caching (for example) depending on the current environment. Example of an `.env.development.py`-file:
```python
DATABASE = {
    'mysql': {
        'ip': '172.0.0.1',
        'user': 'myuser',
        'pass': 'mypass',
    },
}
```

#### Specifying the current environment

The current environment is set with the `APP_ENV`-key. The first way to do this is in the `.env.py`-file as explained before. The second way is by settings an environment-variable `APP_ENV` before creating the application. For example:
```bash
export APP_ENV=production
```
This will load `.env.production.py` when loading up the application.


