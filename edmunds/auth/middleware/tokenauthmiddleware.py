
from edmunds.http.requestmiddleware import RequestMiddleware
from flask_security import auth_token_required


class TokenAuthMiddleware(RequestMiddleware):
    """
    Token Authentication Middleware
    """

    def before(self):
        """
        Handle before the request
        """

        decorator = auth_token_required(lambda: None)
        result = decorator()

        return result
