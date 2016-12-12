
import Edmunds.Support.helpers as helpers
from Edmunds.Profiler.Drivers.BlackfireIo import BlackfireIo
from Edmunds.Profiler.Drivers.CallGraph import CallGraph
from Edmunds.Profiler.Drivers.Stream import Stream
import os
import sys


class ProfilerManager(object):
	"""
	The Log Manager
	"""

	def __init__(self, app):
		"""
		Initiate the manager
		:param app: 	The application
		:type  app: 	Edmunds.Application
		"""

		self._app = app
		self._default_profile_dir = self._app.storage_path('profs')

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
			raise RuntimeError('No profiling-instances declared.')

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

		instances_config = self._app.config('app.profiler.instances')
		for instance_config in instances_config:
			name = instance_config['name']
			if name in self._instances:
				raise RuntimeError('Redeclaring profiling-instance with name "%s"' % name)

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
		instances_config = self._app.config('app.profiler.instances')

		# Pick one
		instance_config = None
		for instances_config_item in instances_config:
			if instances_config_item['name'] == name:
				instance_config = instances_config_item
				break

		# Check if there is one
		if instance_config is None:
			raise RuntimeError('There is no profile-instance declared in the config with name "%s"' % name)

		# Make the driver
		driver_class = instance_config['driver']
		method_name = '_create_%s' % helpers.snake_case(driver_class.__name__)
		driver = getattr(self, method_name)(instance_config)

		return driver


	def _create_blackfire_io(self, config):
		"""
		Create BlackfireIo instance
		:param config:	The config
		:type  config:	dict
		:return:		BlackfireIo instance
		:rtype:			BlackfireIo
		"""

		directory = self._default_profile_dir
		if 'directory' in config:
			directory = config['directory']
			# Check if absolute or relative path
			if not directory.startswith(os.sep):
				directory = self._app.storage_path(directory)

		options = {}

		if 'prefix' in config:
			options['prefix'] = config['prefix']
		if 'name' in config:
			options['suffix'] = '.%s' % config['name']

		return BlackfireIo(self._app, directory, **options)


	def _create_call_graph(self, config):
		"""
		Create CallGraph instance
		:param config:	The config
		:type  config:	dict
		:return:		CallGraph instance
		:rtype:			CallGraph
		"""

		directory = self._default_profile_dir
		if 'directory' in config:
			directory = config['directory']
			# Check if absolute or relative path
			if not directory.startswith(os.sep):
				directory = self._app.storage_path(directory)

		options = {}

		if 'prefix' in config:
			options['prefix'] = config['prefix']
		if 'name' in config:
			options['suffix'] = '.%s' % config['name']

		return CallGraph(self._app, directory, **options)


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
		if 'sort_by' in config:
			options['sort_by'] = config['sort_by']
		if 'restrictions' in config:
			options['restrictions'] = config['restrictions']

		return Stream(self._app, **options)