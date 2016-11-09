
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


	def _handle_route_request_middleware(self, rule, middleware):
		"""
		Handle request middleware from route
		:param rule: 		The rule for routing the request
		:type  rule: 		str
		:param middleware: 	List of middleware
		:type  middleware: 	list
		"""

		for class_ in middleware:
			self._register_request_middleware(class_, rule)


	def _register_request_middleware(self, class_, rule):
		"""
		Add request middleware
		:param class_: 	The class of the middleware
		:type  class_: 	RequestMiddleware
		:param rule: 	The route rule used to identify the middleware
		:type  rule: 	str
		"""

		# add the middleware to the middleware by route
		if rule not in self._request_middleware_by_rule:
			self._request_middleware_by_rule[rule] = []

		self._request_middleware_by_rule[rule].append(class_)


	def _register_request_middleware_handling(self):

		# add a before request
		@self.before_request
		def before_request():

			# initialize the middleware
			if 'request_middleware' not in g:
				g.request_middleware = []

				url_rule = _request_ctx_stack.top.request.url_rule
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
