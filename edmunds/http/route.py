
class Route(object):
    """
    Route object
    """

    def __init__(self, app):
        """
        Initiate the route
        :param app:     Application
        :type app:      edmunds.application.Application
        """
        self.app = app
        self.controller_class = None
        self.method_name = None
        self.decorate_function = None
        self._middleware = []

    def uses(self, controller_class, method_name):
        """
        Register the controller and method that will be used for handling the request
        :param controller_class:    Controller class
        :type controller_class:     str
        :param method_name:         Method name
        :type method_name:          str
        """

        self.controller_class = controller_class
        self.method_name = method_name

    def decorate(self, func):
        """
        Use the route as a decorator
        :param func:    Function that we wrap
        :type func:     callable
        """

        self.decorate_function = func

    def middleware(self, middleware_class, *args, **kwargs):
        """
        Register route middleware
        :param middleware_class:    The middleware class
        :type middleware_class:     class
        :param args:                The middleware arguments
        :type args:                 list
        :param kwargs:              The middleware kwarguments
        :type kwargs:               dict
        :return:                    This instance
        :rtype:                     edmunds.http.route.Route
        """
        assert hasattr(middleware_class, 'before')
        assert hasattr(middleware_class, 'after')

        self._middleware.append((middleware_class, args, kwargs))

        return self

    def handle(self, *args, **kwargs):
        """
        Handle the request
        :param args:    The request arguments
        :type args:     list
        :param kwargs:  The request kwarguments
        :type kwargs:   dict
        :return:        Response
        """
        middleware_instances = []

        # Handle before middleware
        for (middleware_class, middleware_args, middleware_kwargs) in self._middleware:
            middleware_instance = middleware_class(self.app)
            middleware_instances.append((middleware_instance, middleware_args, middleware_kwargs))

            before_result = middleware_instance.before(*middleware_args, **middleware_kwargs)
            if before_result is not None:
                return before_result

        if self.controller_class is not None:
            # Make instance of controller
            controller = self.controller_class(self)

            # Initialize the controller
            controller.initialize(*args, **kwargs)

            # Call method of controller
            method_func = getattr(controller, self.method_name)
            response = method_func(*args, **kwargs)
        else:
            # Call the decorate function
            response = self.decorate_function(*args, **kwargs)

        # Handle after middleware
        for (middleware_instance, middleware_args, middleware_kwargs) in list(reversed(middleware_instances)):
            response = middleware_instance.after(response, *middleware_args, **middleware_kwargs)

        # Return response
        return response
