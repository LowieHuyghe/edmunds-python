
from tests.testcase import TestCase
from edmunds.exceptions.handler import Handler as EdmundsHandler
import edmunds.support.helpers as helpers
from werkzeug.exceptions import default_exceptions, HTTPException
from flask import abort


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

        TestHandler.cache = {}
        TestHandler.cache['timeline'] = []


    def test_http_exceptions(self):
        """
        Test http exceptions
        """

        rule = '/' + helpers.random_str(20)
        abort_exception = None

        # Add route
        @self.app.route(rule)
        def handleRoute():
            TestHandler.cache['timeline'].append('handleRoute')
            abort(abort_exception)
            return ''

        # Check current handler and add new
        self.app.debug = False
        self.assert_not_equal(MyHandler, self.app.config('app.exceptions.handler', None))
        self.app.config({
            'app.exceptions.handler': MyHandler
        })
        self.assert_equal(MyHandler, self.app.config('app.exceptions.handler', None))

        # Call route
        with self.app.test_client() as c:

            # Loop http exceptions
            for http_exception in default_exceptions.values():
                abort_exception = http_exception.code

                TestHandler.cache = {}
                TestHandler.cache['timeline'] = []
                rv = c.get(rule)

                self.assert_equal('rendered', rv.get_data(True))
                self.assert_equal(http_exception.code, rv.status_code)

                self.assert_equal(4, len(TestHandler.cache['timeline']))

                self.assert_in('handleRoute', TestHandler.cache['timeline'])
                self.assert_equal(0, TestHandler.cache['timeline'].index('handleRoute'))

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

        rule = '/' + helpers.random_str(20)

        # Check current handler and add new
        self.app.debug = False
        self.assert_not_equal(MyHandler, self.app.config('app.exceptions.handler', None))
        self.app.config({
            'app.exceptions.handler': MyHandler
        })
        self.assert_equal(MyHandler, self.app.config('app.exceptions.handler', None))

        # Call route
        with self.app.test_client() as c:
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

        rule = '/' + helpers.random_str(20)

        # Add route
        @self.app.route(rule)
        def handleRoute():
            TestHandler.cache['timeline'].append('handleRoute')
            raise RuntimeError('MyRuntimeError')
            return ''

        # Check current handler and add new
        self.app.debug = False
        self.assert_not_equal(MyHandler, self.app.config('app.exceptions.handler', None))
        self.app.config({
            'app.exceptions.handler': MyHandler
        })
        self.assert_equal(MyHandler, self.app.config('app.exceptions.handler', None))

        # Call route
        with self.app.test_client() as c:
            rv = c.get(rule)

            self.assert_equal('rendered', rv.get_data(True))
            self.assert_equal(500, rv.status_code)

            self.assert_equal(4, len(TestHandler.cache['timeline']))

            self.assert_in('handleRoute', TestHandler.cache['timeline'])
            self.assert_equal(0, TestHandler.cache['timeline'].index('handleRoute'))

            self.assert_in(MyHandler.__name__ + '.report', TestHandler.cache['timeline'])
            self.assert_equal(1, TestHandler.cache['timeline'].index(MyHandler.__name__ + '.report'))

            self.assert_in(MyHandler.__name__ + '.render', TestHandler.cache['timeline'])
            self.assert_equal(2, TestHandler.cache['timeline'].index(MyHandler.__name__ + '.render'))

            self.assert_true(isinstance(TestHandler.cache['timeline'][3], RuntimeError))
            self.assert_equal('MyRuntimeError', '%s' % TestHandler.cache['timeline'][3])



class MyHandler(EdmundsHandler):
    """
    Exception Handler class
    """

    def report(self, exception):
        """
        Report the exception
        :param exception:   The exception
        :type  exception:   Exception
        """

        TestHandler.cache['timeline'].append(self.__class__.__name__ + '.report')

        super(MyHandler, self).report(exception)


    def render(self, exception):
        """
        Render the exception
        :param exception:   The exception
        :type  exception:   Exception
        :return:            The response
        """

        TestHandler.cache['timeline'].append(self.__class__.__name__ + '.render')

        TestHandler.cache['timeline'].append(exception)

        rendered_exception, status_code = super(MyHandler, self).render(exception)
        return 'rendered', status_code
