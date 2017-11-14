
# Configuration

The configuration of the application is managed with
configuration-files and environment-files.


## What will be loaded?

All configuration files in the `config`-directory will
be loaded in an arbitrary order.

Secondly environment-configuration will be loaded in
the root of the project. More on this below.

Configuration can this way be overwritten. Environment-config
will overwrite config of the configuration-files if both
define a value for the same key. More on this in Processing.


## Processing

The processing of configuration in Edmunds is slightly
upgraded from Flask's to make it easier to use. The reason
is that Edmunds encourages to use dictionary-structures in
your configuration for readability purposes.

But as mentioned before, files can overwrite values
depending on priority. So how does Edmunds handle this?
Let's take the following example:
```python
# config/generalconfig.py
APP_NAME = 'My App'
APP_AUTHOR = 'Edmunds'
APP = {
    'database': {
        'mysql': {
            'ip': '93.35.46.344',
            'user': 'mydbuser',
            'roles': ['admin', 'user']
        }
    }
}

# .env.py
APP_ENV = 'development'
APP_NAME = 'My App locally'
APP = {
    'database': {
        'mysql': {
            'ip': 'localhost',
            'roles': ['tester'],
            'pass': 'verysecure'
        }
    }
}
```
Using the default behaviour of Flask, `.env.py` would completely
overwrite `APP` defined in `config/generalconfig.py`. Edmunds on
the other hand will merge dictionaries (only dictionaries!) and
overwrite where needed. Giving the example above this would be
the loaded result:
```python
APP_ENV = 'development'
APP_NAME = 'My App locally'
APP_AUTHOR = 'Edmunds'
APP = {
    'database': {
        'mysql': {
            'ip': 'localhost',
            'user': 'mydbuser',
            'roles': ['tester'],
            'pass': 'verysecure'
        }
    }
}
```

## Fetching

Fetching configuration can be done by accessing the config
as a dictionary. Or by using the added helper-methods.
Using the above defined configuration, we can access the
config like so:
```python
has_app_name = app.config.has('app.name')
app_name = app.config('app.name', default=None)
# or
app_name = app.config('APP_NAME')
app_name = app.config['APP_NAME']

db_ip = app.config('app.database.mysql.ip')
# or
db_ip = app.config('APP_DATABASE_MYSQL_IP')
db_ip = app.config['APP']['database']['mysql']['ip']

db_user = app.config('app.database.mysql.user')
# or
db_user = app.config('APP_DATABASE_MYSQL_USER')
db_user = app.config['APP']['database']['mysql']['user']

has_db_pass = app.config.has('app.database.mysql.pass')
# or
has_db_pass = 'pass' in app.config['APP']['database']['mysql']
```

> Note: Using underscores (`_`) or dots (`.`) in the key of a
> dict in your configuration will not work with `app.config()`
> and `app.config.has()`.  
> Ex: `APP = {'info_name': 'Edmunds'}` will not work.


## Environment configuration

Some configuration is specific to the runtime environment.
It depends on the machine it is running on, or on the
current environment (development, production or testing).
This configuration is not included in the git-repository,
which makes it ideal for security sensitive configuration
(like the app-secret-key).

The environment configuration is specified in the
`.env.py`-files in the root of the project. An example
of a `.env.py`-file:
```python
SECRET_KEY = 'aFBHjD8SHhqj71LEEmoxc8bLH4lzUTr'
APP = {
    'env': 'development'
}
# or
# APP_ENV = 'development'
```

The current environment given in the above example
(`app.env` being `development`) will try to load more specific
configuration in `.env.development.py`. This allows you to
use separate databases or caching (for example) depending on
the current environment. Example of a
`.env.development.py`-file:
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

The current environment is by default set in the `.env.py`-file
as described above. The second way, which has priority over the
first method, is by settings an environment-variable `APP_ENV`.
For example:
```bash
export APP_ENV=production
```
This will load `.env.production.py` when running the
application.
