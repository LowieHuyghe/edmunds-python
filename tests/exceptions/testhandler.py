
from tests.testcase import TestCase
from edmunds.exceptions.handler import Handler as EdmundsHandler
from werkzeug.exceptions import default_exceptions, HTTPException
from edmunds.globals import abort


class TestHandler(TestCase):
    """
    Test the Exception Handler
    """

    cache = None

    def set_up(self):
        """
        Set up the test case
        """

        super(TestHandler, self).set_up()

        TestHandler.cache = dict()
        TestHandler.cache['timeline'] = []

    def test_http_exceptions(self):
        """
        Test http exceptions
        """

        rule = '/' + self.rand_str(20)
        abort_exception = None

        self.write_config([
            "from tests.exceptions.testhandler import MyHandler \n",
            "APP = { \n",
            "    'debug': False, \n",
            "    'exceptions': { \n",
            "        'handler': MyHandler \n",
            "    } \n",
            "} \n",
        ])
        app = self.create_application()

        # Add route
        @app.route(rule)
        def handle_route():
            TestHandler.cache['timeline'].append('handle_route')
            abort(abort_exception)
            return 'handled route'

        # Check current handler
        self.assert_equal(MyHandler, app.config('app.exceptions.handler', None))

        # Call route
        with app.test_client() as c:

            # Loop http exceptions
            for http_exception in default_exceptions.values():
                abort_exception = http_exception.code

                TestHandler.cache = dict()
                TestHandler.cache['timeline'] = []
                rv = c.get(rule)

                # THIS IS NOT OK! See https://github.com/pallets/werkzeug/issues/1231
                # Waiting for fix to be released.
                if http_exception.code != 412:
                    self.assert_equal('rendered', rv.get_data(True))
                self.assert_equal(http_exception.code, rv.status_code)

                self.assert_equal(4, len(TestHandler.cache['timeline']))

                self.assert_in('handle_route', TestHandler.cache['timeline'])
                self.assert_equal(0, TestHandler.cache['timeline'].index('handle_route'))

                self.assert_in(MyHandler.__name__ + '.report', TestHandler.cache['timeline'])
                self.assert_equal(1, TestHandler.cache['timeline'].index(MyHandler.__name__ + '.report'))

                self.assert_in(MyHandler.__name__ + '.render', TestHandler.cache['timeline'])
                self.assert_equal(2, TestHandler.cache['timeline'].index(MyHandler.__name__ + '.render'))

                self.assert_true(isinstance(TestHandler.cache['timeline'][3], HTTPException))
                self.assert_equal(http_exception.code, TestHandler.cache['timeline'][3].code)

    def test_404(self):
        """
        Test 404
        """

        rule = '/' + self.rand_str(20)

        self.write_config([
            "from tests.exceptions.testhandler import MyHandler \n",
            "APP = { \n",
            "    'debug': False, \n",
            "    'exceptions': { \n",
            "        'handler': MyHandler \n",
            "    } \n",
            "} \n",
        ])
        app = self.create_application()

        # Check current handler
        self.assert_equal(MyHandler, app.config('app.exceptions.handler', None))

        # Call route
        with app.test_client() as c:
            rv = c.get(rule)

            self.assert_equal('rendered', rv.get_data(True))
            self.assert_equal(404, rv.status_code)

            self.assert_equal(3, len(TestHandler.cache['timeline']))

            self.assert_in(MyHandler.__name__ + '.report', TestHandler.cache['timeline'])
            self.assert_equal(0, TestHandler.cache['timeline'].index(MyHandler.__name__ + '.report'))

            self.assert_in(MyHandler.__name__ + '.render', TestHandler.cache['timeline'])
            self.assert_equal(1, TestHandler.cache['timeline'].index(MyHandler.__name__ + '.render'))

            self.assert_true(isinstance(TestHandler.cache['timeline'][2], HTTPException))
            self.assert_equal(404, TestHandler.cache['timeline'][2].code)

    def test_exception(self):
        """
        Test generic exception
        """

        rule = '/' + self.rand_str(20)

        self.write_config([
            "from tests.exceptions.testhandler import MyHandler \n",
            "APP = { \n",
            "    'debug': False, \n",
            "    'exceptions': { \n",
            "        'handler': MyHandler \n",
            "    } \n",
            "} \n",
        ])
        app = self.create_application()

        # Add route
        @app.route(rule)
        def handle_route():
            TestHandler.cache['timeline'].append('handle_route')
            raise RuntimeError('MyRuntimeError')

        # Check current handler
        self.assert_equal(MyHandler, app.config('app.exceptions.handler', None))

        # Call route
        with app.test_client() as c:
            rv = c.get(rule)

            self.assert_equal('rendered', rv.get_data(True))
            self.assert_equal(500, rv.status_code)

            self.assert_equal(4, len(TestHandler.cache['timeline']))

            self.assert_in('handle_route', TestHandler.cache['timeline'])
            self.assert_equal(0, TestHandler.cache['timeline'].index('handle_route'))

            self.assert_in(MyHandler.__name__ + '.report', TestHandler.cache['timeline'])
            self.assert_equal(1, TestHandler.cache['timeline'].index(MyHandler.__name__ + '.report'))

            self.assert_in(MyHandler.__name__ + '.render', TestHandler.cache['timeline'])
            self.assert_equal(2, TestHandler.cache['timeline'].index(MyHandler.__name__ + '.render'))

            self.assert_true(isinstance(TestHandler.cache['timeline'][3], RuntimeError))
            self.assert_equal('MyRuntimeError', '%s' % TestHandler.cache['timeline'][3])

    def test_dont_report(self):
        """
        Test dont_report
        :return:    void
        """

        rule1 = '/' + self.rand_str(20)
        rule2 = '/' + self.rand_str(20)

        self.write_config([
            "from tests.exceptions.testhandler import MyHandler \n",
            "APP = { \n",
            "    'debug': False, \n",
            "    'exceptions': { \n",
            "        'handler': MyHandler \n",
            "    } \n",
            "} \n",
        ])
        app = self.create_application()

        # Add route
        @app.route(rule1)
        def handle_route1():
            TestHandler.cache['timeline'].append('handle_route1')
            raise SystemError()

        @app.route(rule2)
        def handle_route2():
            TestHandler.cache['timeline'].append('handle_route2')
            raise OSError()

        # Call route
        with app.test_client() as c:
            c.get(rule1)
        with app.test_client() as c:
            c.get(rule2)

            self.assert_equal(7, len(TestHandler.cache['timeline']))

            self.assert_equal(0, TestHandler.cache['timeline'].index('handle_route1'))
            self.assert_equal('handle_route1', TestHandler.cache['timeline'][0])
            self.assert_equal(MyHandler.__name__ + '.report', TestHandler.cache['timeline'][1])
            self.assert_equal(MyHandler.__name__ + '.render', TestHandler.cache['timeline'][2])
            self.assert_true(isinstance(TestHandler.cache['timeline'][3], SystemError))
            self.assert_equal('handle_route2', TestHandler.cache['timeline'][4])
            self.assert_equal(MyHandler.__name__ + '.render', TestHandler.cache['timeline'][5])
            self.assert_true(isinstance(TestHandler.cache['timeline'][6], OSError))

    def test_raise_exception_debug(self):
        """
        Test raise exception in debug environment
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        self.write_config([
            "from tests.exceptions.testhandler import MyHandler \n",
            "APP = { \n",
            "    'debug': True, \n",
            "    'exceptions': { \n",
            "        'handler': MyHandler \n",
            "    } \n",
            "} \n",
        ])
        app = self.create_application()

        # Add route
        @app.route(rule)
        def handle_route():
            raise RuntimeError(rule)

        # Call route
        with self.assert_raises_regexp(RuntimeError, rule):
            with app.test_client() as c:
                c.get(rule)


class MyHandler(EdmundsHandler):
    """
    Exception Handler class
    """

    dont_report = [
        OSError
    ]

    def report(self, exception):
        """
        Report the exception
        :param exception:   The exception
        :type  exception:   Exception
        """

        if super(MyHandler, self).report(exception):
            TestHandler.cache['timeline'].append(self.__class__.__name__ + '.report')

    def render(self, exception):
        """
        Render the exception
        :param exception:   The exception
        :type  exception:   Exception
        :return:            The response
        """

        TestHandler.cache['timeline'].append(self.__class__.__name__ + '.render')

        TestHandler.cache['timeline'].append(exception)

        response = super(MyHandler, self).render(exception)
        return 'rendered', response.status_code
