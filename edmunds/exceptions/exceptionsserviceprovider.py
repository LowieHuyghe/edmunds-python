
from edmunds.support.serviceprovider import ServiceProvider
from werkzeug.exceptions import default_exceptions
from edmunds.exceptions.handler import Handler


class ExceptionsServiceProvider(ServiceProvider):
	"""
	Exceptions Service Provider
	"""

	def register(self):
		"""
		Register the service provider
		"""

		# Add all the exception to handle
		exceptions = default_exceptions.values()
		exceptions.append(Exception)

		# Register each exception
		for exception_class in exceptions:

			@self.app.errorhandler(exception_class)
			def handle_exception(exception):
				"""
				Handle an exception
				:param exception: 	The exception
				:type  exception: 	Exception
				:return:			The response
				"""

				handler_class = self.app.config('app.exceptions.handler', Handler)
				handler = handler_class(self.app)

				handler.report(exception)
				return handler.render(exception)