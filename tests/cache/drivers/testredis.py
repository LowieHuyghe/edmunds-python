
from tests.testcase import TestCase
from werkzeug.contrib.cache import RedisCache
from edmunds.cache.drivers.redis import Redis


class TestRedis(TestCase):
    """
    Test the Redis
    """

    def test_redis(self):
        """
        Test the redis
        """

        # Write config
        self.write_config([
            "from edmunds.cache.drivers.redis import Redis \n",
            "APP = { \n",
            "   'cache': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'redis',\n",
            "               'driver': Redis,\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
            ])

        # Create app
        app = self.create_application()

        driver = app.cache()
        self.assert_is_instance(driver, Redis)
        self.assert_is_instance(driver, RedisCache)
