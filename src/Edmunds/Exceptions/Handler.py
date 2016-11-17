
class Handler(object):
	"""
	The Exception handler
	"""

	def __init__(self, app):
		"""
		Initiate
		:param app:		The application
		:type  app: 	Edmunds.Application
		"""

		self.app = app


	def report(self, exception):
		"""
		Report the exception
		:param exception: 	The exception
		:type  exception: 	Exception
		"""
		pass


	def render(self, exception):
		"""
		Render the exception
		:param exception: 	The exception
		:type  exception: 	Exception
		:return: 			The response
		"""
		return 'Hell Yeah!'