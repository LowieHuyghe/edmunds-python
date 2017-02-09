
import edmunds.support.helpers as helpers
from flask import request as flask_request


class RequestRouting(object):
	"""
	This class concerns request routing code for Application to extend from
	"""

	def _init_request_routing(self):
		"""
		Initiate the request routing
		"""

		self._pre_request_uses_by_rule = {}
		self._request_uses_by_rule = {}


	def _pre_handle_route_dispatching(self, rule, options):
		"""
		Pre handle route request dispatching
		:param rule: 		The rule for routing the request
		:type  rule: 		str
		:param options: 	List of options
		:type  options: 	list
		"""

		uses = options.pop('uses', None)
		if uses is None:
			return;

		# Validate
		class_, method = uses
		assert hasattr(class_, method)

		# Add uses up front
		self._pre_request_uses_by_rule[rule] = uses

		# Set unique endpoint
		if 'endpoint' not in options:
			options['endpoint'] = '%s.%s' % (helpers.get_full_class_name(class_), method)


	def _post_handle_route_dispatching(self, decorator, rule, options):
		"""
		Post handle route request dispatching
		:param decorator:	The decorator function
		:type  decorator:	callable
		:param rule: 		The rule for routing the request
		:type  rule: 		str
		:param options: 	List of options
		:type  options: 	list
		:return:			Decorator function to call
		:rtype:				callable
		"""

		# Empty uses
		if rule not in self._pre_request_uses_by_rule:
			return decorator

		# Fetch uses
		uses = self._pre_request_uses_by_rule.pop(rule)
		self._request_uses_by_rule[rule] = uses

		# Make handler
		def handler(**kwargs):
			return self.dispatch(flask_request)

		# Call decorator
		decorator(handler)

		# Return none
		return None


	def dispatch(self, request = None):
		"""
		Dispatch a request
		:param request: 	The request
		:type  request:		Request
		:return:			The response
		:rtype:				str
		"""

		# Assign current request
		if request is None:
			request = flask_request

		# Fetch rule
		rule = request.url_rule.rule
		if rule not in self._request_uses_by_rule:
			raise RuntimeError('Dispatching request that was not defined with \'uses\': %s.' % rule)

		# Fetch uses, class and method
		uses = self._request_uses_by_rule[rule]
		class_, method = uses


		# Make instance of controller
		controller = class_(self)

		# Initialize the controller
		controller.initialize(**request.view_args)

		# Call method of controller
		method_func = getattr(controller, method)
		response = method_func(**request.view_args)

		# Return the response
		return response