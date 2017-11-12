
from werkzeug.contrib.cache import MemcachedCache

from edmunds.cache.drivers.memcached import Memcached
from tests.gaetestcase import GaeTestCase

if GaeTestCase.can_run():
    from google.appengine.api import memcache


class TestMemcachedInGae(GaeTestCase):
    """
    Test the Memcached in Google App Engine
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestMemcachedInGae, self).set_up()

        self.testbed.init_memcache_stub()

    def test_memcached_in_gae(self):
        """
        Test the memcached in Google App Engine
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
        self.assert_is_instance(driver._client, memcache.Client)
