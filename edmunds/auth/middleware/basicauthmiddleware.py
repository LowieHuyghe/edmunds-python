
from edmunds.http.requestmiddleware import RequestMiddleware
from flask_security import http_auth_required


class BasicAuthMiddleware(RequestMiddleware):
    """
    Basic Authentication Middleware
    """

    def before(self):
        """
        Handle before the request
        """

        wrapper = http_auth_required(None)
        decorator = wrapper(lambda: None)
        result = decorator()

        return result
