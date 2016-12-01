
from pyprof2calltree import convert
import os.path


class Callgrind(object):
	"""
	Callgrind driver
	"""

	def __init__(self, config, default_profile_directory):
		"""
		Initiate the instance
		:param config:						The config of the driver
		:type  config:						dict
		:param default_profile_directory: 	The default directory to put the files
		:type  default_profile_directory: 	str
		"""

		self._profile_dir = config['directory'] if 'directory' in config else default_profile_directory


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

		filename = os.path.join(self._profile_dir, suggestive_file_name + '.callgrind')

		convert(profiler.getstats(), filename)