
from Edmunds.Application import Application as EdmundsApplication
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


	def storage_path(self, path):
		"""
		Get the storage path to a file
		:param path: 	The path to the file
		:type  path: 	str
		:return:		The complete path
		:rtype: 		str
		"""

		return '/storage'