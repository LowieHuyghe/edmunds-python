
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.globals import request, _request_ctx_stack
from edmunds.http.visitor import Visitor


class HttpServiceProvider(ServiceProvider):
    """
    Http Service Provider
    """

    def register(self):
        """
        Register the service provider
        """

        @self.app.before_request
        def before_request():
            ctx = _request_ctx_stack.top
            if ctx is not None:
                visitor = Visitor(self.app, request)
                setattr(ctx, 'edmunds.visitor', visitor)
