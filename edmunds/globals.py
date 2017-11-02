
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
    send_from_directory as flask_send_from_directory, \
    jsonify as flask_jsonify, \
    _request_ctx_stack as flask__request_ctx_stack
from functools import partial
from werkzeug.local import LocalProxy


abc = builtin_abc
ABC = abc.ABCMeta('ABC', (object,), {})

current_app = flask_current_app
request = flask_request
session = flask_session
has_request_context = flask_has_request_context
make_response = flask_make_response
abort = flask_abort
g = flask_g
_request_ctx_stack = flask__request_ctx_stack
render_template = flask_render_template
redirect = flask_redirect
send_from_directory = flask_send_from_directory
jsonify = flask_jsonify


def _lookup_req_object(name):
    top = _request_ctx_stack.top
    if top is None:
        raise RuntimeError('''\
Working outside of request context.

This typically means that you attempted to use functionality that needed
an active HTTP request.  Consult the documentation on testing for
information about how to avoid this problem.\
''')
    if not hasattr(top, name):
        raise RuntimeError('''\
Request context has not been pre-processed.

This typically means that you attempted to use functionality that has
not been initialized yet. Usually it's because flask.Flask.preprocess_request
has not been called yet.\
''')
    return getattr(top, name)


visitor = LocalProxy(partial(_lookup_req_object, 'edmunds.visitor'))
