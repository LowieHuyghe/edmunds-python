
from edmunds.http.requestmiddleware import RequestMiddleware
from flask_security import roles_required


class RolesRequiredMiddleware(RequestMiddleware):
    """
    Roles required Middleware
    """

    def before(self, *roles):
        """
        Handle before the request
        :param roles:   Roles required
        :type roles:    list
        """

        wrapper = roles_required(*roles)
        decorator = wrapper(lambda: None)
        result = decorator()

        return result
