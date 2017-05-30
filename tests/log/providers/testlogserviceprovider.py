
from tests.testcase import TestCase
from edmunds.log.logmanager import LogManager


class TestLogServiceProvider(TestCase):
    """
    Test the Log Service Provider
    """

    def test_logging_disabled(self):
        """
        Test logging disabled
        """

        log_string = 'LogServiceProviderTest::test_logging_disabled'

        # Write config
        self.write_config([
            "from edmunds.log.drivers.stream import Stream \n",
            "try: \n",
            "   from cStringIO import StringIO \n",
            "except ImportError: \n",
            "   from io import StringIO \n",
            "APP = { \n",
            "   'debug': False, \n",
            "   'log': { \n",
            "       'enabled': False, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'stream',\n",
            "               'driver': Stream,\n",
            "               'stream': StringIO(),\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()
        stream = app.config('app.log.instances')[0]['stream']

        # Test extension
        self.assert_not_in('edmunds.log', app.extensions)

        # Add route
        rule = '/' + self.rand_str(20)
        @app.route(rule)
        def handle_route():
            app.logger.error(log_string)
            return ''

        with app.test_client() as c:

            # Check log files
            self.assert_not_in(log_string, stream.getvalue())

            # Call route
            c.get(rule)

            # Check log files
            self.assert_not_in(log_string, stream.getvalue())

    def test_logging_enabled(self):
        """
        Test logging enabled
        """

        log_string = 'LogServiceProviderTest::test_logging_enabled'

        # Write config
        self.write_config([
            "from edmunds.log.drivers.stream import Stream \n",
            "try: \n",
            "   from cStringIO import StringIO \n",
            "except ImportError: \n",
            "   from io import StringIO \n",
            "APP = { \n",
            "   'debug': False, \n",
            "   'log': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'stream',\n",
            "               'driver': Stream,\n",
            "               'stream': StringIO(),\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()
        stream = app.config('app.log.instances')[0]['stream']

        # Test extension
        self.assert_in('edmunds.log', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.log'])
        self.assert_is_instance(app.extensions['edmunds.log'], LogManager)

        # Add route
        rule = '/' + self.rand_str(20)
        @app.route(rule)
        def handle_route():
            app.logger.error(log_string)
            return ''

        with app.test_client() as c:

            # Check log files
            self.assert_not_in(log_string, stream.getvalue())

            # Call route
            c.get(rule)

            # Check log files
            self.assert_in(log_string, stream.getvalue())

    def test_multiple_loggers(self):
        """
        Test logging enabled
        """

        log_string = 'LogServiceProviderTest::test_logging_enabled'

        # Write config
        self.write_config([
            "from edmunds.log.drivers.stream import Stream \n",
            "try: \n",
            "   from cStringIO import StringIO \n",
            "except ImportError: \n",
            "   from io import StringIO \n",
            "APP = { \n",
            "   'debug': False, \n",
            "   'log': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'stream',\n",
            "               'driver': Stream,\n",
            "               'stream': StringIO(),\n",
            "           }, \n",
            "           { \n",
            "               'name': 'stream2',\n",
            "               'driver': Stream,\n",
            "               'stream': StringIO(),\n",
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()
        stream = app.config('app.log.instances')[0]['stream']
        stream2 = app.config('app.log.instances')[1]['stream']

        # Test extension
        self.assert_in('edmunds.log', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.log'])
        self.assert_is_instance(app.extensions['edmunds.log'], LogManager)

        # Add route
        rule = '/' + self.rand_str(20)
        @app.route(rule)
        def handle_route():
            app.logger.error(log_string)
            return ''

        with app.test_client() as c:

            # Check log files
            self.assert_not_in(log_string, stream.getvalue())
            self.assert_not_in(log_string, stream2.getvalue())

            # Call route
            c.get(rule)

            # Check log files
            self.assert_in(log_string, stream.getvalue())
            self.assert_in(log_string, stream2.getvalue())
