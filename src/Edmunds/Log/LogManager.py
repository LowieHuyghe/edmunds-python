
from Edmunds.Foundation.Patterns.Manager import Manager
import Edmunds.Support.helpers as helpers
from Edmunds.Log.Drivers.File import File
from Edmunds.Log.Drivers.TimedFile import TimedFile
from Edmunds.Log.Drivers.Stream import Stream
from Edmunds.Gae.RuntimeEnvironment import RuntimeEnvironment as GaeRuntimeEnvironment
import os
# Only import when not running in Google App Engine
if not GaeRuntimeEnvironment().is_gae():
	from Edmunds.Log.Drivers.SysLog import SysLog


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

		super(LogManager, self).__init__(app, app.config('app.log.instances'))

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
				directory = self._app.storage_path(directory)

		filename = '%s.log' % self._app.name

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
				directory = self._app.storage_path(directory)

		filename = '%s.log' % self._app.name

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

		return Stream(self._app, **options)
