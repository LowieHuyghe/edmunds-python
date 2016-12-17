
from Edmunds.Storage.Drivers.BaseDriver import BaseDriver
import os


class File(BaseDriver):
	"""
	File driver
	"""

	def __init__(self, app, directory, prefix = ''):
		"""
		Initiate the instance
		:param app: 			The application
		:type  app: 			Edmunds.Application
		:param directory:		The directory
		:type  directory:		str
		:param prefix: 			The prefix for storing
		:type  prefix: 			str
		"""

		super(File, self).__init__(app)

		self._storage_dir = directory
		self._prefix = prefix


	def write_stream(self, path):
		"""
		Get a write stream to a certain path
		:param path: 	The path to the file
		:type  path: 	str
		:return:		The write stream
		:rtype: 		Stream
		"""

		path = self._get_processed_path(path)

		return open(path, 'w+')


	def read_stream(self, path):
		"""
		Get a read stream to a certain path
		:param path: 	The path to the file
		:type  path: 	str
		:return:		The write stream
		:rtype: 		Stream
		"""

		path = self._get_processed_path(path)

		return open(path, 'r')


	def _get_processed_path(self, path):
		"""
		Get the processed path
		:param path: 	The path to the file
		:type  path: 	str
		:return:		The processed path to the file
		:rtype: 		str
		"""

		path_parts = path.split(os.sep)

		filename = path_parts.pop()
		filename = self._prefix + filename
		path_parts.append(filename)

		path = os.sep.join(path_parts)

		if not path.startswith(os.sep):
			path = os.path.join(self._storage_dir, path)

		return path