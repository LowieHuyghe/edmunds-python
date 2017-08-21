
from edmunds.globals import g, request


class Middleware(object):
    """
    This class concerns middleware code for Application to extend from
    """

    def _init_middleware(self):
        """
        Initialise concerning middleware
        """

        self._registered_application_middleware = []
        self._pre_request_middleware_by_rule = {}
        self._request_middleware_by_rule = {}

        self._register_request_middleware_handling()

    def middleware(self, class_):
        """
        Add Application middleware
        :param class_:  The class of the middleware
        :type  class_:  ApplicationMiddleware
        """

        # Only register a middleware once
        if class_ in self._registered_application_middleware:
            return
        self._registered_application_middleware.append(class_)

        # add wsgi application
        self.wsgi_app = class_(self)

    def _pre_handle_route_middleware(self, rule, options):
        """
        Pre handle request middleware from route
        :param rule:        The rule for routing the request
        :type  rule:        str
        :param options:     List of options
        :type  options:     list
        """

        # Add middleware
        middleware = options.pop('middleware', None)
        if middleware is None:
            return

        # Validate
        for class_ in middleware:
            assert hasattr(class_, 'before')
            assert hasattr(class_, 'after')

        # Add middleware upfront
        self._pre_request_middleware_by_rule[rule] = middleware

    def _post_handle_route_middleware(self, decorator, rule, options):
        """
        Post handle request middleware from route
        :param decorator:   The decorator function
        :type  decorator:   callable
        :param rule:        The rule for routing the request
        :type  rule:        str
        :param options:     List of options
        :type  options:     list
        :return:            Decorator function to call
        :rtype:             callable
        """

        # Empty middleware
        if rule not in self._pre_request_middleware_by_rule:
            return decorator

        # Fetch middleware
        middleware = self._pre_request_middleware_by_rule.pop(rule)

        # Register middleware when decorator is called
        def register_middleware(f):
            res = decorator(f)
            self._request_middleware_by_rule[rule] = middleware
            return res

        return register_middleware

    def _register_request_middleware_handling(self):
        """
        Register the request middleware handling with before and after request
        """

        # add a before request
        @self.before_request
        def before_request():

            # initialize the middleware
            if 'request_middleware' not in g:
                g.request_middleware = []

                url_rule = request.url_rule
                if url_rule is not None:
                    rule = url_rule.rule

                    if rule in self._request_middleware_by_rule:
                        for class_ in self._request_middleware_by_rule[rule]:
                            g.request_middleware.append(class_(self))

            # loop middleware
            for middleware in g.request_middleware:
                rv = middleware.before()
                if rv is not None:
                    return rv

            # return default
            return None

        # add a after request
        @self.after_request
        def after_request(response):

            # loop middleware reversed
            for middleware in g.request_middleware[::-1]:
                response = middleware.after(response)

            # return response
            return response
