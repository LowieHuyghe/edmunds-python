
from edmunds.http.requestmiddleware import RequestMiddleware
from flask_security import login_required


class SessionAuthMiddleware(RequestMiddleware):
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
