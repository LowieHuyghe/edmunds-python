
from tests.testcase import TestCase


class TestProfilerMiddleware(TestCase):
    """
    Test the Profiler Middleware
    """

    def test_profiler_disabled(self):
        """
        Test profiler disabled
        """

        # Write config
        self.write_config([
            "from edmunds.profiler.drivers.stream import Stream \n",
            "try: \n",
            "   from cStringIO import StringIO \n",
            "except ImportError: \n",
            "   from io import StringIO \n",
            "APP = { \n",
            "   'debug': True, \n",
            "   'profiler': { \n",
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

        # Create app and fetch stream
        app = self.create_application()
        stream = app.config('app.profiler.instances')[0]['stream']

        # Add route
        rule = '/' + self.rand_str(20)
        @app.route(rule)
        def handle_route():
            return ''

        with app.test_client() as c:

            # Check stream
            self.assert_equal('', stream.getvalue())

            # Call route
            c.get(rule)

            # Check stream
            self.assert_equal('', stream.getvalue())

    def test_app_no_debug(self):
        """
        Test app no debug
        """

        # Write config
        self.write_config([
            "from edmunds.profiler.drivers.stream import Stream \n",
            "try: \n",
            "   from cStringIO import StringIO \n",
            "except ImportError: \n",
            "   from io import StringIO \n",
            "APP = { \n",
            "   'debug': False, \n",
            "   'profiler': { \n",
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

        # Create app and fetch stream
        app = self.create_application()
        stream = app.config('app.profiler.instances')[0]['stream']

        # Add route
        rule = '/' + self.rand_str(20)
        @app.route(rule)
        def handle_route():
            return ''

        with app.test_client() as c:

            # Check stream
            self.assert_equal('', stream.getvalue())

            # Call route
            c.get(rule)

            # Check stream
            self.assert_equal('', stream.getvalue())

    def test_app_no_debug_profiler_disabled(self):
        """
        Test app no debug and profiler disabled
        """

        # Write config
        self.write_config([
            "from edmunds.profiler.drivers.stream import Stream \n",
            "try: \n",
            "   from cStringIO import StringIO \n",
            "except ImportError: \n",
            "   from io import StringIO \n",
            "APP = { \n",
            "   'debug': False, \n",
            "   'profiler': { \n",
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

        # Create app and fetch stream
        app = self.create_application()
        stream = app.config('app.profiler.instances')[0]['stream']

        # Add route
        rule = '/' + self.rand_str(20)
        @app.route(rule)
        def handle_route():
            return ''

        with app.test_client() as c:

            # Check stream
            self.assert_equal('', stream.getvalue())

            # Call route
            c.get(rule)

            # Check stream
            self.assert_equal('', stream.getvalue())

    def test_enabled(self):
        """
        Test enabled
        """

        # Write config
        self.write_config([
            "from edmunds.profiler.drivers.stream import Stream \n",
            "try: \n",
            "   from cStringIO import StringIO \n",
            "except ImportError: \n",
            "   from io import StringIO \n",
            "APP = { \n",
            "   'debug': True, \n",
            "   'profiler': { \n",
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

        # Create app and fetch stream
        app = self.create_application()
        stream = app.config('app.profiler.instances')[0]['stream']

        # Add route
        rule = '/' + self.rand_str(20)
        @app.route(rule)
        def handle_route():
            return ''

        with app.test_client() as c:

            # Check stream
            self.assert_equal('', stream.getvalue())

            # Call route
            c.get(rule)

            # Check stream
            self.assert_not_equal('', stream.getvalue())

    def test_multiple_profilers(self):
        """
        Test multiple profilers
        """

        # Write config
        self.write_config([
            "from edmunds.profiler.drivers.stream import Stream \n",
            "try: \n",
            "   from cStringIO import StringIO \n",
            "except ImportError: \n",
            "   from io import StringIO \n",
            "APP = { \n",
            "   'debug': True, \n",
            "   'profiler': { \n",
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

        # Create app and fetch stream
        app = self.create_application()
        stream = app.config('app.profiler.instances')[0]['stream']
        stream2 = app.config('app.profiler.instances')[1]['stream']

        # Add route
        rule = '/' + self.rand_str(20)
        @app.route(rule)
        def handle_route():
            return ''

        with app.test_client() as c:

            # Check stream
            self.assert_equal('', stream.getvalue())
            self.assert_equal('', stream2.getvalue())
            self.assert_equal(stream.getvalue(), stream2.getvalue())

            # Call route
            c.get(rule)

            # Check stream
            self.assert_not_equal('', stream.getvalue())
            self.assert_not_equal('', stream2.getvalue())
            self.assert_equal(stream.getvalue(), stream2.getvalue())
