
from edmunds.http.requestmiddleware import RequestMiddleware
from flask_security import roles_accepted


class RolesAcceptedMiddleware(RequestMiddleware):
    """
    Roles accepted Middleware
    """

    def before(self, *roles):
        """
        Handle before the request
        :param roles:   Roles accepted
        :type roles:    list
        """

        wrapper = roles_accepted(*roles)
        decorator = wrapper(lambda: None)
        result = decorator()

        return result
