
from pstats import Stats
import sys


class Stream(object):
	"""
	Stream driver
	"""

	def __init__(self, app, config, default_profile_directory):
		"""
		Initiate the instance
		:param app: 						The application
		:type  app: 						Edmunds.Application
		:param config:						The config of the driver
		:type  config:						dict
		:param default_profile_directory: 	The default directory to put the files
		:type  default_profile_directory: 	str
		"""

		self.app = app
		self._stream = config.stream if 'stream' in config else sys.stdout
		self._sort_by = config.sort_by if 'sorty_by' in config else ('time', 'calls')
		self._restrictions = config.restrictions if 'restrictions' in config else ()


	def process(self, profiler, start, end, environment, suggestive_file_name):
		"""
		Process the results
		:param profiler:  				The profiler
		:type  profiler: 				cProfile.Profile
		:param start:					Start of profiling
		:type start: 					int
		:param end:						End of profiling
		:type end: 						int
		:param environment: 			The environment
		:type  environment: 			Environment
		:param suggestive_file_name: 	A suggestive file name
		:type  suggestive_file_name: 	str
		"""

		stats = Stats(profiler, stream=self._stream)
		stats.sort_stats(*self._sort_by)

		self._stream.write('-' * 80)
		self._stream.write('\nPATH: %s\n' % environment.get('PATH_INFO'))
		stats.print_stats(*self._restrictions)
		self._stream.write('-' * 80 + '\n\n')