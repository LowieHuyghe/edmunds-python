
from edmunds.http.requestmiddleware import RequestMiddleware
from flask_security import http_auth_required


class BasicAuthMiddleware(RequestMiddleware):
    """
    Basic Authentication Middleware
    """

    def before(self, realm=None):
        """
        Handle before the request
        :param realm:   The realm name
        :type  realm:   string
        """

        wrapper = http_auth_required(realm)
        decorator = wrapper(lambda: None)
        result = decorator()

        return result

    def after(self, response, realm=None):
        """
        Handle after the request
        :param response:    The request response
        :type  response:    Request
        :param realm:       The realm name
        :type  realm:       string
        :return:            The request response
        :rtype:             Request
        """

        return response
