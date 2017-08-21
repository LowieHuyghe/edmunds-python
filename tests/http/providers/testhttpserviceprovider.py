
from tests.testcase import TestCase
from edmunds.globals import _request_ctx_stack
from edmunds.http.visitor import Visitor


class TestHttpServiceProvider(TestCase):
    """
    Test the Http Service Provider
    """

    def test_visitor(self):
        """
        Test visitor
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # No context
        self.assert_is_none(_request_ctx_stack.top)

        # Call route
        with self.app.test_request_context(rule):
            # Before pre-processing
            self.assert_is_not_none(_request_ctx_stack.top)
            self.assert_false(hasattr(_request_ctx_stack.top, 'edmunds.visitor'))

            # Pre-processing
            self.app.preprocess_request()

            # After pre-processing
            self.assert_is_not_none(_request_ctx_stack.top)
            self.assert_true(hasattr(_request_ctx_stack.top, 'edmunds.visitor'))
            self.assert_is_instance(getattr(_request_ctx_stack.top, 'edmunds.visitor'), Visitor)
