
from Edmunds.Foundation.Patterns.Manager import Manager
import Edmunds.Support.helpers as helpers
from Edmunds.Profiler.Drivers.BlackfireIo import BlackfireIo
from Edmunds.Profiler.Drivers.CallGraph import CallGraph
from Edmunds.Profiler.Drivers.Stream import Stream
import os
import sys


class ProfilerManager(Manager):
	"""
	The Log Manager
	"""

	def __init__(self, app):
		"""
		Initiate the manager
		:param app: 	The application
		:type  app: 	Edmunds.Application
		"""

		super(ProfilerManager, self).__init__(app, app.config('app.profiler.instances', []))

		self._default_profile_dir = self._app.storage_path('profs')


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
				directory = os.path.join(self._default_profile_dir, directory)

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
				directory = os.path.join(self._default_profile_dir, directory)

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
