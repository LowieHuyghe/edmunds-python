
from threading import Lock


class Middleware(object):
    """
    This class concerns middleware code for Application to extend from
    """

    def middleware(self, class_):
        """
        Add Application middleware
        :param class_:  The class of the middleware
        :type  class_:  edmunds.foundation.applicationmiddleware.ApplicationMiddleware
        """

        lock_key = 'edmunds.applicationmiddleware.lock'
        middleware_key = 'edmunds.applicationmiddleware.middleware'

        # Register the lock
        if lock_key not in self.extensions:
            self.extensions[lock_key] = Lock()

        # Define list for application middleware
        if middleware_key not in self.extensions:
            with self.extensions[lock_key]:
                if middleware_key not in self.extensions:
                    self.extensions[middleware_key] = []

        # Only register a middleware once
        if class_ in self.extensions[middleware_key]:
            return
        self.extensions[middleware_key].append(class_)

        # add wsgi application
        self.wsgi_app = class_(self)
