
# Configuration

The configuration of the application is managed with
configuration-files and environment-files.

Configuration-files are usually found in the
`config`-directory and handle all non-secret config.
Environment-files will be in the root-directory and are
used for environment-specific and secret config.


## Processing

The processing of configuration in Edmunds is slightly
upgraded from Flask's to make it easier to use. The
original way still works as is. Edmunds merely added
a layer for the developer's comfort.

An example config:
```python
APP_NAME = 'My App'
APP = {
    'database': {
        'mysql': {
            'ip': '127.0.0.1',
            'user': 'mydbuser',
        }
    }
}
```
This will be processed to:
```python
APP_NAME = 'My App'
APP_DATABASE_MYSQL_IP = '127.0.0.1'
APP_DATABASE_MYSQL_USER = 'mydbuser'
```

### What will be loaded?

All configuration files in the `config`-directory will
be loaded in an arbitrary order.

Secondly environment-configuration will be loaded in
the root of the project. More on this below.

Configuration can this way be overwritten. Environment-config
will overwrite config of the configuration-files if both
define a value for the same key.


## Fetching

Fetching configuration can be like accessing the config
as a dictionary. Or by using the added helper-methods.
Using the above defined configuration, we can access the
config like so:
```python
app_name = app.config('app.name')
# or
app_name = app.config['APP_NAME']

db_ip = app.config('app.database.mysql.ip')
# or
db_ip = app.config['APP_DATABASE_MYSQL_IP']

db_user = app.config('app.database.mysql.user')
# or
db_user = app.config['APP_DATABASE_MYSQL_USER']

has_db_pass = app.config.has('app.database.mysql.pass')
# or
has_db_pass = 'APP_DATABASE_MYSQL_PASS' in app.config
```


## Updating

Updating values at runtime can be done like this:
```python
app.config({
    'app.database.mysql.ip': 'localhost',
})
# or
app.config['APP_DATABASE_MYSQL_IP'] = 'localhost'
```


## Environment configuration

Some configuration is specific to the runtime environment.
It depends on the machine it is running on, or on the
current environment (development, production or testing).
This configuration is not included in the git-repository,
which makes it ideal for security sensitive configuration
(like the app-secret-key).

The environment configuration is specified in the
`.env.py`-files in the root of the project. An example
of an `.env.py`-file:
```python
SECRET_KEY = 'aFBHjD8SHhqj71LEEmoxc8bLH4lzUTr'
APP = {
    'env': 'development'
}
```

The current environment given in the above example
(`APP_ENV`) will try to load more specific configuration
in `.env.development.py`. This allows you to use separate
databases or caching (for example) depending on the current
environment. Example of an `.env.development.py`-file:
```python
DATABASE = {
    'mysql': {
        'ip': '172.0.0.1',
        'user': 'myuser',
        'pass': 'mypass',
    },
}
```

### Specifying the current environment

The current environment is set with the `APP_ENV`-key.
The first way to do this is in the `.env.py`-file as
explained before. The second way is by settings an
environment-variable `APP_ENV` before running the
application. For example:
```bash
export APP_ENV=production
```
This will load `.env.production.py` when running the
application.
