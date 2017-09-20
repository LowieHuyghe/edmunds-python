
from tests.testcase import TestCase
from edmunds.cache.cachemanager import CacheManager


class TestCacheServiceProvider(TestCase):
    """
    Test the Cache Service Provider
    """

    def test_not_enabled(self):
        """
        Test not enabled
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.cache.drivers.memcached import Memcached \n",
            "APP = { \n",
            "   'cache': { \n",
            "       'enabled': False, \n",
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

        # Test extension
        self.assert_not_in('edmunds.cache', app.extensions)

    def test_register(self):
        """
        Test register
        :return:    void
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

        # Test extension
        self.assert_in('edmunds.cache', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.cache'])
        self.assert_is_instance(app.extensions['edmunds.cache'], CacheManager)
