
from pyprof2calltree import CalltreeConverter
import os


class CallGraph(object):
	"""
	CallGraph driver
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

		if 'directory' in config:
			self._profile_dir = config['directory']
			# Check if absolute or relative path
			if not self._profile_dir.startswith(os.sep):
				self._profile_dir = self.app.storage_path(self._profile_dir)
		else:
			self._profile_dir = default_profile_directory

		self.prefix = config['prefix'] if 'prefix' in config else ''


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

		filename = os.path.join(self._profile_dir, self.prefix + suggestive_file_name + '.callgraph')

		converter = CalltreeConverter(profiler.getstats())
		f = self.app.write_stream(filename)

		try:
			converter.output(f)
		finally:
			f.close()
