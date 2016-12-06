
from pyprof2calltree import CalltreeConverter
import os
import cProfile


class BlackfireIo(object):
	"""
	Blackfire Io driver
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

		filename = os.path.join(self._profile_dir, self.prefix + suggestive_file_name + '.blackfireio')

		converter = CalltreeConverter(profiler.getstats())

		f = self.app.write_stream(filename)

		try:
			f.write('file-format: BlackfireProbe\n')
			f.write('cost-dimensions: wt\n')
			f.write('request-start: %d\n' % start)
			f.write('profile-title: %s\n' % self.prefix + suggestive_file_name)
			f.write('\n')

			def unique_name(code):
				co_filename, co_firstlineno, co_name = cProfile.label(code)
				munged_name = converter.munged_function_name(code)
				return '%s::%s' % (co_filename, munged_name)

			def _entry_sort_key(entry):
				return cProfile.label(entry.code)


			for entry in sorted(converter.entries, key=_entry_sort_key):

				entry_name = unique_name(entry.code)
				if entry_name.startswith('~::'):
					continue

				if entry.calls:
					for subentry in sorted(entry.calls, key=_entry_sort_key):
						subentry_name = unique_name(subentry.code)
						if subentry_name.startswith('~::'):
							continue

						f.write('%s==>%s//%i %i\n' % (entry_name, subentry_name, subentry.callcount, int(subentry.totaltime * 1e9)))
		finally:
			f.close()