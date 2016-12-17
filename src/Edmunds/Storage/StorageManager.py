
from Edmunds.Foundation.Patterns.Manager import Manager
import Edmunds.Support.helpers as helpers
import os


class StorageManager(Manager):
	"""
	Storage Manager
	"""

	def __init__(self, app):
		"""
		Initiate the manager
		:param app: 	The application
		:type  app: 	Edmunds.Application
		"""

		super(StorageManager, self).__init__(app, app.config('app.storage.instances', []))

		self._default_log_dir = self._app.storage_path('files')


	def _create_file(self, config):
		"""
		Create File instance
		:param config:	The config
		:type  config:	dict
		:return:		File instance
		:rtype:			File
		"""

		directory = self._default_log_dir
		if 'directory' in config:
			directory = config['directory']
			# Check if absolute or relative path
			if not directory.startswith(os.sep):
				directory = os.path.join(self._default_log_dir, directory)

		options = {}

		if 'prefix' in config:
			options['prefix'] = config['prefix']

		from Edmunds.Storage.Drivers.File import File
		return File(self._app, directory, **options)


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

		directory = self._default_log_dir
		if 'directory' in config:
			directory = config['directory']
			# Check if absolute or relative path
			if not directory.startswith(os.sep):
				directory = os.path.join(self._default_log_dir, directory)

		options = {}

		if 'prefix' in config:
			options['prefix'] = config['prefix']

		from Edmunds.Storage.Drivers.GoogleCloudStorage import GoogleCloudStorage
		return GoogleCloudStorage(self._app, bucket, directory, **options)
