
from Edmunds.Foundation.Patterns.Manager import Manager
import os


class StorageManager(Manager):
	"""
	Storage Manager
	"""

	def __init__(self, app, root_path, storage_path):
		"""
		Initiate the manager
		:param app: 			The application
		:type  app: 			Edmunds.Application
		:param root_path: 		The root path
		:type  root_path: 		str
		:param storage_path: 	The storage path
		:type  storage_path: 	str
		"""

		super(StorageManager, self).__init__(app, app.config('app.storage.instances', []))

		self._root_path = root_path
		self._storage_path = storage_path
		self._files_path = 'files'


	def _create_file(self, config):
		"""
		Create File instance
		:param config:	The config
		:type  config:	dict
		:return:		File instance
		:rtype:			File
		"""

		storage_path = os.path.join(self._root_path, self._storage_path)
		if 'directory' in config:
			directory = config['directory']
			# Check if absolute or relative path
			if not directory.startswith(os.sep):
				storage_path = os.path.join(storage_path, directory)
			else:
				storage_path = os.path.join(self._root_path, directory[1:])

		files_path = self._files_path
		if 'files_path' in config:
			files_path = config['files_path']

		options = {}

		if 'prefix' in config:
			options['prefix'] = config['prefix']

		from Edmunds.Storage.Drivers.File import File
		return File(self._app, storage_path, files_path, **options)


	def _create_google_cloud_storage(self, config):
		"""
		Create GoogleCloudStorage instance
		:param config:	The config
		:type  config:	dict
		:return:		GoogleCloudStorage instance
		:rtype:			GoogleCloudStorage
		"""

		from google.appengine.api import app_identity
		bucket = app_identity.get_default_gcs_bucket_name()
		if 'bucket' in config:
			bucket = config['bucket']

		storage_path = os.path.join(os.sep, self._storage_path)
		if 'directory' in config:
			directory = config['directory']
			# Check if absolute or relative path
			if not directory.startswith(os.sep):
				storage_path = os.path.join(storage_path, directory)
			else:
				storage_path = directory

		files_path = self._files_path
		if 'files_path' in config:
			files_path = config['files_path']

		options = {}

		if 'prefix' in config:
			options['prefix'] = config['prefix']

		from Edmunds.Storage.Drivers.GoogleCloudStorage import GoogleCloudStorage
		return GoogleCloudStorage(self._app, bucket, storage_path, files_path, **options)
