
from edmunds.http.requestmiddleware import RequestMiddleware
from flask_principal import Permission, RoleNeed
from flask import abort


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

        perms = [Permission(RoleNeed(role)) for role in roles]
        for perm in perms:
            if not perm.can():
                abort(403)

    def after(self, response, *roles):
        """
        Handle after the request
        :param response:    The request response
        :type  response:    Request
        :param roles:       Roles accepted
        :type roles:        list
        :return:            The request response
        :rtype:             Request
        """

        return response
