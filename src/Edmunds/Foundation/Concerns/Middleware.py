
from flask import g, _request_ctx_stack


class Middleware(object):
	"""
	This class concerns middleware code for Application to extend from
	"""

	def _init_middleware(self):
		"""
		Initialise concerning middleware
		"""

		self._registered_application_middleware = []
		self._request_middleware_by_rule = {}

		self._register_request_middleware_handling()


	def middleware(self, class_):
		"""
		Add Application middleware
		:param class_: 	The class of the middleware
		:type  class_: 	ApplicationMiddleware
		"""

		# Only register a middleware once
		if class_ in self._registered_application_middleware:
			return
		self._registered_application_middleware.append(class_)

		# add wsgi application
		self.wsgi_app = class_(self)


	def _handle_route_request_middleware(self, decorator, rule, middleware):
		"""
		Handle request middleware from route
		:param decorator:	The decorator function
		:type  decorator:	callable
		:param rule: 		The rule for routing the request
		:type  rule: 		str
		:param middleware: 	List of middleware
		:type  middleware: 	list
		:return:			Decorator function to call
		:rtype:				callable
		"""

		# Empty middleware
		if middleware is None:
			return decorator

		# Validate
		for class_ in middleware:
			assert hasattr(class_, 'before')
			assert hasattr(class_, 'after')

		# Register middleware when decorator is called
		def register_middleware(f):
			res = decorator(f)
			self._request_middleware_by_rule[rule] = middleware
			return res

		return register_middleware


	def _register_request_middleware_handling(self):

		# add a before request
		@self.before_request
		def before_request():

			# initialize the middleware
			if 'request_middleware' not in g:
				g.request_middleware = []

				url_rule = _request_ctx_stack.top.request.url_rule
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
