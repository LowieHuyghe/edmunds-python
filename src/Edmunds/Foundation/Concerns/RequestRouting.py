
import Edmunds.Support.helpers as helpers


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
		class_, method = uses

		# Make handler
		def handler():
			return self.dispatch(class_, method)

		# Call decorator
		decorator(handler)

		# Return none
		return None


	def dispatch(self, class_, method):
		"""
		Dispatch a request
		:param class_:	The class of the controller
		:type  class_:	class
		:param method:	Method to call
		:type  method:	str
		:return:		The response
		:rtype:			str
		"""

		# Make instance of controller
		controller = class_()

		# Initialize the controller
		controller.initialize()

		# Call method of controller
		method_func = getattr(controller, method)
		response = method_func()

		# Return the response
		return response