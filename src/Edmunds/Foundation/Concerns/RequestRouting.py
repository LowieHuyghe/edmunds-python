
class RequestRouting(object):
	"""
	This class concerns request routing code for Application to extend from
	"""

	def _init_request_routing(self):
		"""
		Initiate the request routing
		"""
		pass


	def _handle_route_request_dispatching(self, decorator, rule, uses):
		"""
		Handle route request dispatching
		:param decorator:	The decorator function
		:type  decorator:	callable
		:param rule: 		The rule for routing the request
		:type  rule: 		str
		:param uses: 		Tuple of class and method to call
		:type  uses: 		tuple
		:return:			Decorator function to call
		:rtype:				callable
		"""

		# Empty uses
		if uses is None:
			return decorator

		# Validate
		class_, method = uses
		assert hasattr(class_, method)

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