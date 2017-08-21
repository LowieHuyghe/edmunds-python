
from tests.testcase import TestCase
import abc as builtin_abc
from flask import current_app as flask_current_app, \
    request as flask_request, \
    session as flask_session, \
    has_request_context as flask_has_request_context, \
    make_response as flask_make_response, \
    abort as flask_abort, \
    g as flask_g, \
    render_template as flask_render_template, \
    redirect as flask_redirect, \
    send_file as flask_send_file, \
    jsonify as flask_jsonify, \
    _request_ctx_stack as flask__request_ctx_stack
from werkzeug.local import LocalProxy
from edmunds.globals import abc, \
    ABC, \
    current_app, \
    request, \
    session, \
    has_request_context, \
    make_response, \
    abort, \
    g, \
    _request_ctx_stack, \
    render_template, \
    redirect, \
    send_file, \
    jsonify, \
    visitor
from edmunds.http.visitor import Visitor


class TestGlobals(TestCase):
    """
    Test the Globals
    """

    def test_globals(self):
        """
        Test globals
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        self.assert_equal_deep(builtin_abc, abc)
        self.assert_is_instance(ABC, abc.ABCMeta)

        with self.app.test_request_context(rule):
            self.assert_equal_deep(flask_current_app, current_app)
            self.assert_equal_deep(flask_request, request)
            self.assert_equal_deep(flask_session, session)
            self.assert_equal_deep(flask_g, g)

        self.assert_equal_deep(flask_has_request_context, has_request_context)
        self.assert_equal_deep(flask_make_response, make_response)
        self.assert_equal_deep(flask_abort, abort)
        self.assert_equal_deep(flask__request_ctx_stack, _request_ctx_stack)
        self.assert_equal_deep(flask_render_template, render_template)
        self.assert_equal_deep(flask_redirect, redirect)
        self.assert_equal_deep(flask_send_file, send_file)
        self.assert_equal_deep(flask_jsonify, jsonify)

        self.assert_is_instance(visitor, LocalProxy)

    def test_proxies(self):
        """
        Test proxies
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Outside context
        with self.assert_raises_regexp(RuntimeError, 'Working outside of request context'):
            self.assert_is_none(visitor._get_current_object())

        # Call route
        with self.app.test_request_context(rule):
            # Before pre-processing
            with self.assert_raises_regexp(RuntimeError, 'Request context has not been pre-processed'):
                self.assert_is_none(visitor._get_current_object())

            # Pre-processing
            self.app.preprocess_request()

            # After pre-processing
            self.assert_is_instance(visitor, LocalProxy)
            self.assert_is_not_none(visitor._get_current_object())
            self.assert_is_instance(visitor._get_current_object(), Visitor)
