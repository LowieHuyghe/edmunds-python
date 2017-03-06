
class RequestMiddleware(object):
    """
    The Request Middleware
    """

    def __init__(self, app):
        """
        Initialize the application
        :param app:     The application
        :type  app:     Edmunds.Application
        """

        self.app = app

    def before(self):
        """
        Handle before the request
        """

        return None

    def after(self, response):
        """
        Handle after the request
        :param response:    The request response
        :type  response:    Request
        :return:            The request response
        :rtype:             Request
        """

        return response
