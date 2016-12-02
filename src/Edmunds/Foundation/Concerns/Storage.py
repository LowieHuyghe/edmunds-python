
import os


class Storage(object):
	"""
	This class concerns storage code for Application to extend from
	"""

	def storage_path(self, path):
		"""
		Get the storage path to a file
		:param path: 	The path to the file
		:type  path: 	str
		:return:		The complete path
		:rtype: 		str
		"""

		return os.path.join(self.root_path, 'storage', path)


	def write_stream(self, path):
		"""
		Get a write stream to a certain path
		:param path: 	The path to the file
		:type  path: 	str
		:return:		The write stream
		:rtype: 		Stream
		"""

		if not path.startswith(os.sep):
			path = self.storage_path(path)

		return open(path, 'w+')


	def read_stream(self, path):
		"""
		Get a read stream to a certain path
		:param path: 	The path to the file
		:type  path: 	str
		:return:		The write stream
		:rtype: 		Stream
		"""

		if not path.startswith(os.sep):
			path = self.storage_path(path)

		return open(path, 'r')
