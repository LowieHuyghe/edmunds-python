
from werkzeug.exceptions import default_exceptions, HTTPException
from Edmunds.Exceptions.Handler import Handler


class ExceptionHandling(object):
	"""
	This class concerns exception handling code for Application to extend from
	"""

	def _init_exception_handling(self):
		"""
		Initiate the exception handling
		"""

		# Add all the exception to handle
		exceptions = default_exceptions.values()
		exceptions.append(Exception)

		# Register each exception
		for exception_class in exceptions:

			@self.errorhandler(exception_class)
			def handle_exception(exception):
				"""
				Handle an exception
				:param exception: 	The exception
				:type  exception: 	Exception
				:return:			The response
				"""

				handler = Handler(self)

				handler.report(exception)
				return handler.render(exception)
