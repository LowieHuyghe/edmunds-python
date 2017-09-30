
from edmunds.auth.middleware.basemiddleware import BaseMiddleware
from flask_security import login_required


class SessionAuthMiddleware(BaseMiddleware):
    """
    Session Authentication Middleware
    """

    def before(self):
        """
        Handle before the request
        """

        decorator = login_required(lambda: None)
        result = decorator()

        return result
