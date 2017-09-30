
from edmunds.auth.middleware.basemiddleware import BaseMiddleware
from flask_security import auth_token_required


class TokenAuthMiddleware(BaseMiddleware):
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
