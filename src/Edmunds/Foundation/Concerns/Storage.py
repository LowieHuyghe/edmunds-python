
from Edmunds.Storage.StorageManager import StorageManager
import os


class Storage(object):
	"""
	This class concerns storage code for Application to extend from
	"""

	def _init_storage(self):
		"""
		Initialise concerning storage
		"""

		self._storage_manager = StorageManager(self)


	def storage_path(self, path):
		"""
		Get the storage path to a file
		:param path: 	The path to the file
		:type  path: 	str
		:return:		The complete path
		:rtype: 		str
		"""

		return os.path.join(self.root_path, 'storage', path)


	def write_stream(self, path, name = None):
		"""
		Get a write stream to a certain path
		:param path: 	The path to the file
		:type  path: 	str
		:param name: 	The name of the storage instance
		:type  name: 	str
		:return:		The write stream
		:rtype: 		Stream
		"""

		instance = self._storage_manager.get(name)

		return instance.write_stream(path)


	def read_stream(self, path, name = None):
		"""
		Get a read stream to a certain path
		:param path: 	The path to the file
		:type  path: 	str
		:param name: 	The name of the storage instance
		:type  name: 	str
		:return:		The write stream
		:rtype: 		Stream
		"""

		instance = self._storage_manager.get(name)

		return instance.read_stream(path)
