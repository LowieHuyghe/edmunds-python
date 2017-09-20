
from edmunds.foundation.patterns.manager import Manager
from edmunds.cache.drivers.file import File
from edmunds.cache.drivers.memcached import Memcached
from edmunds.cache.drivers.redis import Redis
import os


class CacheManager(Manager):
    """
    Cache Manager
    """

    def __init__(self, app):
        """
        Initiate the manager
        :param app:     The application
        :type  app:     edmunds.application.Application
        """

        super(CacheManager, self).__init__(app, app.config('app.cache.instances', []))

        self._cache_path = os.path.join(os.sep, 'cache')

    def _create_file(self, config):
        """
        Create File instance
        :param config:  The config
        :type  config:  dict
        :return:        File instance
        :rtype:         File
        """

        cache_path = self._cache_path
        if 'directory' in config:
            directory = config['directory']
            # Check if absolute or relative path
            if not directory.startswith(os.sep):
                cache_path = os.path.join(cache_path, directory)
            else:
                cache_path = directory
        if not cache_path.endswith(os.sep):
            cache_path = cache_path + os.sep

        cache_dir = self._app.fs().path(cache_path)

        options = {}

        if 'threshold' in config:
            options['threshold'] = config['threshold']
        if 'default_timeout' in config:
            options['default_timeout'] = config['default_timeout']
        if 'mode' in config:
            options['mode'] = config['mode']

        return File(cache_dir, **options)

    def _create_redis(self, config):
        """
        Create Redis instance
        :param config:  The config
        :type  config:  dict
        :return:        Redis instance
        :rtype:         Redis
        """

        options = {}

        if 'host' in config:
            options['host'] = config['host']
        if 'port' in config:
            options['port'] = config['port']
        if 'password' in config:
            options['password'] = config['password']
        if 'db' in config:
            options['db'] = config['db']
        if 'default_timeout' in config:
            options['default_timeout'] = config['default_timeout']
        if 'key_prefix' in config:
            options['key_prefix'] = config['key_prefix']

        return Redis(**options)

    def _create_memcached(self, config):
        """
        Create Memcached instance
        :param config:  The config
        :type  config:  dict
        :return:        Redis instance
        :rtype:         Redis
        """

        options = {}

        if 'servers' in config:
            options['servers'] = config['servers']
        if 'default_timeout' in config:
            options['default_timeout'] = config['default_timeout']
        if 'key_prefix' in config:
            options['key_prefix'] = config['key_prefix']

        return Memcached(**options)
