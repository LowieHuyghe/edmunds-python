
from edmunds.application import Application as EdmundsApplication
from google.appengine.api import modules


class Application(EdmundsApplication):
	"""
	The Google App Engine Edmunds Application
	"""

	def app_id(self):
		"""
		Get the app id
		:return: 	The app id
		:rtype:		str
		"""

		return modules.get_current_instance_id()
