
import Edmunds.Support.helpers as helpers
from Edmunds.Log.Drivers.File import File
from Edmunds.Log.Drivers.TimedFile import TimedFile
from Edmunds.Log.Drivers.SysLog import SysLog


class LogManager(object):
	"""
	Log Manager
	"""

	def __init__(self, app):
		"""
		Initiate the manager
		:param app: 	The application
		:type  app: 	Edmunds.Application
		"""

		self._app = app
		self._default_log_dir = self._app.storage_path('logs')

		self._load()


	def get(self, name = None):
		"""
		Get the instance
		:param name: 	The name of the instance
		:type  name:	str
		:return:		The driver
		:rtype:			BaseDriver
		"""

		if len(self._instances) == 0:
			raise RuntimeError('No log-instances declared.')

		if name is None:
			name = self._instances.keys()[0]

		return self._instances[name]


	def all(self):
		"""
		Get all the instances
		"""

		return self._instances.values()


	def _load(self):
		"""
		Load all the instances
		"""

		self._instances = {}

		instances_config = self._app.config('app.log.instances')
		for instance_config in instances_config:
			name = instance_config['name']
			if name in self._instances:
				raise RuntimeError('Redeclaring log-instance with name "%s"' % name)

			self._instances[name] = self._resolve(name)


	def _resolve(self, name):
		"""
		Resolve the instance
		:param name:	The name of the instance
		:type  name:	str
		:return:		The driver
		:rtype:			BaseDriver
		"""

		# Fetch config
		instances_config = self._app.config('app.log.instances')

		# Pick one
		instance_config = None
		for instances_config_item in instances_config:
			if instances_config_item['name'] == name:
				instance_config = instances_config_item
				break

		# Check if there is one
		if instance_config is None:
			raise RuntimeError('There is no log-instance declared in the config with name "%s"' % name)

		# Make the driver
		driver_class = instance_config['driver']
		method_name = '_create_%s' % helpers.snake_case(driver_class.__name__)
		driver = getattr(self, method_name)(instance_config)

		return driver


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

		if 'host' in config:
			options['host'] = config['host']
		if 'port' in config:
			options['port'] = config['port']
		if 'facility' in config:
			options['facility'] = config['facility']

		return SysLog(self._app, **options)
