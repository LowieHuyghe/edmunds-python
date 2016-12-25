
from Edmunds.Foundation.Patterns.Manager import Manager
import Edmunds.Support.helpers as helpers
import os


class LogManager(Manager):
	"""
	Log Manager
	"""

	def __init__(self, app):
		"""
		Initiate the manager
		:param app: 	The application
		:type  app: 	Edmunds.Application
		"""

		super(LogManager, self).__init__(app, app.config('app.log.instances', []))

		self._default_log_dir = self._app.storage_path('logs')


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

		filename = '%s.log' % 'app' # self._app.name

		options = {}

		if 'prefix' in config:
			options['prefix'] = config['prefix']
		if 'max_bytes' in config:
			options['max_bytes'] = config['max_bytes']
		if 'backup_count' in config:
			options['backup_count'] = config['backup_count']
		if 'level' in config:
			options['level'] = config['level']
		if 'format' in config:
			options['format'] = config['format']

		from Edmunds.Log.Drivers.File import File
		return File(self._app, directory, filename, **options)


	def _create_timed_file(self, config):
		"""
		Create TimedFile instance
		:param config:	The config
		:type  config:	dict
		:return:		TimedFile instance
		:rtype:			TimedFile
		"""

		directory = self._default_log_dir
		if 'directory' in config:
			directory = config['directory']
			# Check if absolute or relative path
			if not directory.startswith(os.sep):
				directory = os.path.join(self._default_log_dir, directory)

		filename = '%s.log' % 'app' # self._app.name

		options = {}

		if 'prefix' in config:
			options['prefix'] = config['prefix']
		if 'when' in config:
			options['when'] = config['when']
		if 'interval' in config:
			options['interval'] = config['interval']
		if 'backup_count' in config:
			options['backup_count'] = config['backup_count']
		if 'level' in config:
			options['level'] = config['level']
		if 'format' in config:
			options['format'] = config['format']

		from Edmunds.Log.Drivers.TimedFile import TimedFile
		return TimedFile(self._app, directory, filename, **options)


	def _create_sys_log(self, config):
		"""
		Create SysLog instance
		:param config:	The config
		:type  config:	dict
		:return:		SysLog instance
		:rtype:			SysLog
		"""

		options = {}

		if 'address' in config:
			options['address'] = config['address']
		if 'facility' in config:
			options['facility'] = config['facility']
		if 'socktype' in config:
			options['socktype'] = config['socktype']
		if 'level' in config:
			options['level'] = config['level']
		if 'format' in config:
			options['format'] = config['format']

		from Edmunds.Log.Drivers.SysLog import SysLog
		return SysLog(self._app, **options)


	def _create_stream(self, config):
		"""
		Create Stream instance
		:param config:	The config
		:type  config:	dict
		:return:		Stream instance
		:rtype:			Stream
		"""

		options = {}

		if 'stream' in config:
			options['stream'] = config['stream']
		if 'level' in config:
			options['level'] = config['level']
		if 'format' in config:
			options['format'] = config['format']

		from Edmunds.Log.Drivers.Stream import Stream
		return Stream(self._app, **options)


	def _create_google_app_engine(self, config):
		"""
		Create GoogleAppEngine instance
		:param config:	The config
		:type  config:	dict
		:return:		GoogleAppEngine instance
		:rtype:			GoogleAppEngine
		"""

		options = {}

		if 'stream' in config:
			options['stream'] = config['stream']
		if 'level' in config:
			options['level'] = config['level']
		if 'format' in config:
			options['format'] = config['format']

		from Edmunds.Log.Drivers.GoogleAppEngine import GoogleAppEngine
		return GoogleAppEngine(self._app, **options)
