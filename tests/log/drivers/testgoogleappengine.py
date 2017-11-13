
from tests.gaetestcase import GaeTestCase
import os
from logging import StreamHandler, ERROR
try:
   from cStringIO import StringIO
except ImportError:
   from io import StringIO


class TestGoogleAppEngine(GaeTestCase):
    """
    Test the GoogleAppEngine
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestGoogleAppEngine, self).set_up()

        self.testbed.init_logservice_stub()

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestGoogleAppEngine, self).tear_down()

        if 'SERVER_SOFTWARE' in os.environ:
            del os.environ['SERVER_SOFTWARE']

    def test_logger(self):
        """
        Test the logger
        """

        # Write config
        self.write_config([
            "from edmunds.log.drivers.googleappengine import GoogleAppEngine \n",
            "from logging import ERROR \n",
            "APP = { \n",
            "   'debug': False, \n",
            "   'log': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'googleappengine',\n",
            "               'driver': GoogleAppEngine,\n",
            "               'level': ERROR,\n"
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])
        os.environ['SERVER_SOFTWARE'] = 'Google App Engine/%s' % self.rand_str()

        # Create app and fetch stream
        app = self.create_application()

        from google.appengine.api.app_logging import AppLogsHandler
        logger = app.logger.handlers[-1]
        self.assert_is_instance(logger, AppLogsHandler)
        self.assert_equal(ERROR, logger.level)

        self.assert_is_none(logger.development_handler)

    def test_logger_development(self):
        """
        Test the logger in development
        """

        rule = '/' + self.rand_str(20)
        info_string = 'info_%s' % self.rand_str(20)
        warning_string = 'warning_%s' % self.rand_str(20)
        error_string = 'error_%s' % self.rand_str(20)

        # Write config
        self.write_config([
            "from edmunds.log.drivers.googleappengine import GoogleAppEngine \n",
            "from logging import ERROR \n",
            "APP = { \n",
            "   'debug': False, \n",
            "   'log': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'googleappengine',\n",
            "               'driver': GoogleAppEngine,\n",
            "               'level': ERROR,\n"
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])
        os.environ['SERVER_SOFTWARE'] = 'Development/%s' % self.rand_str()

        # Create app and fetch stream
        app = self.create_application()

        from google.appengine.api.app_logging import AppLogsHandler
        logger = app.logger.handlers[-1]
        self.assert_is_instance(logger, AppLogsHandler)
        self.assert_equal(ERROR, logger.level)

        self.assert_is_not_none(logger.development_handler)
        self.assert_is_instance(logger.development_handler, StreamHandler)
        self.assert_equal(ERROR, logger.development_handler.level)

        # Test streaming
        stream = StringIO()
        logger.development_handler.stream = stream

        @app.route(rule)
        def handle_route():
            app.logger.info(info_string)
            app.logger.warning(warning_string)
            app.logger.error(error_string)
            return ''

        with app.test_client() as c:

            # Check stream
            self.assert_not_in(info_string, stream.getvalue())
            self.assert_not_in(warning_string, stream.getvalue())
            self.assert_not_in(error_string, stream.getvalue())

            # Call route
            c.get(rule)

            # Check stream
            self.assert_not_in(info_string, stream.getvalue())
            self.assert_not_in(warning_string, stream.getvalue())
            self.assert_in(error_string, stream.getvalue())
