
from tests.testcase import TestCase
from werkzeug.contrib.cache import MemcachedCache
from edmunds.cache.drivers.memcached import Memcached


class TestMemcached(TestCase):
    """
    Test the Memcached
    """

    def test_memcached(self):
        """
        Test the memcached
        """

        # Write config
        self.write_config([
            "from edmunds.cache.drivers.memcached import Memcached \n",
            "APP = { \n",
            "   'cache': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'memcached',\n",
            "               'driver': Memcached,\n",
            "               'servers': ['127.0.0.1:11211'], \n",
            "               'default_timeout': 300, \n",
            "               'key_prefix': None, \n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
            ])

        # Create app
        app = self.create_application()

        driver = app.cache()
        self.assert_is_instance(driver, Memcached)
        self.assert_is_instance(driver, MemcachedCache)
