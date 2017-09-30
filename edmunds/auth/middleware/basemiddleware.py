
from edmunds.http.requestmiddleware import RequestMiddleware
from flask_principal import Permission, RoleNeed


class BaseMiddleware(RequestMiddleware):
    """
    Base Middleware
    """

    def _require_roles(self, *roles):
        """
        Require roles
        """

        perms = [Permission(RoleNeed(role)) for role in roles]
        for perm in perms:
            if not perm.can():
                return False
        return True

    def _accepted_roles(self, *roles):
        """
        Accepted roles
        """

        perms = [Permission(RoleNeed(role)) for role in roles]
        for perm in perms:
            if perm.can():
                return True
        return False
