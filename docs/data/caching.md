
# Caching

Cache usage is built in in Edmunds and uses
[Werkzeug Cache](http://werkzeug.pocoo.org/docs/0.12/contrib/cache/).

## Settings

You can set your caching preferences in the settings:
```python
from edmunds.cache.drivers.file import File
from edmunds.cache.drivers.memcached import Memcached
from edmunds.cache.drivers.redis import Redis

APP = {
    'cache':
    {
        'enabled': True,
        'instances':
        [
            {
                'name': 'file',
                'driver': File,
                # 'directory': '',          # Optional, default: '' (= logs/)
                # 'threshold': 500,         # Optional, default: 500
                # 'default_timeout': 300,   # Optional, default: 300
                # 'mode': 0o600,            # Optional, default: 0o600
            },
            {
                'name': 'memcached',
                'driver': Memcached,
                # 'servers': ['127.0.0.1:11211'],   # Optional, default: ['127.0.0.1:11211']
                # 'default_timeout': 300,           # Optional, default: 300
                # 'key_prefix': None,               # Optional, default: None
            },
            {
                'name': 'redis',
                'driver': Redis,
                # 'host': 'localhost',      # Optional, default: 'localhost'
                # 'port': 6379,             # Optional, default: 6379
                # 'password': None,         # Optional, default: None
                # 'db': 0,                  # Optional, default: 0
                # 'default_timeout': 300,   # Optional, default: 300
                # 'key_prefix': None,       # Optional, default: None
            },
        ],
    },
}
```
The instances can be used for database, so you can have multiple at once.
The first one will be used by default.

The available drivers are:

- **File**: For caching using files.
- **Memcached**: For Memcached caching.
- **Redis**: For Redis caching.

This configuration is based off the original arguments of the Werkzeug cache
drivers. So more information regarding configuration can be found in the
Werkzeug documentation:

* [Werkzeug Cache](http://werkzeug.pocoo.org/docs/0.12/contrib/cache/)


## Usage

When fetching an instance, you will receive a cache-driver
(`werkzeug.contrib.cache.BaseCache`) for the specified cache instance.
You can request one like so:
```python
# Fetch the default driver, or by name
driver = app.cache()
driver = app.cache(name='memcached')
```

Further usage of the cache-driver is described in the Werkzeug documentation:

* [Werkzeug Cache](http://werkzeug.pocoo.org/docs/0.12/contrib/cache/)
