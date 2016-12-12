
from Edmunds.Profiler.Drivers.BaseDriver import BaseDriver
from pyprof2calltree import CalltreeConverter
import os


class CallGraph(BaseDriver):
	"""
	CallGraph driver
	"""

	def __init__(self, app, directory, prefix = ''):
		"""
		Initiate the instance
		:param app: 			The application
		:type  app: 			Edmunds.Application
		:param directory:		The directory
		:type  directory:		str
		:param prefix: 			The prefix for storing
		:type  prefix: 			str
		"""

		super(CallGraph, self).__init__(app)

		self._profile_dir = directory
		self._prefix = prefix


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

		filename = os.path.join(self._profile_dir, self._prefix + suggestive_file_name + '.callgraph')

		converter = CalltreeConverter(profiler.getstats())
		f = self._app.write_stream(filename)

		try:
			converter.output(f)
		finally:
			f.close()
