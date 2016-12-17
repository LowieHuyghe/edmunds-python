
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers
import os


class TimedFileTest(TestCase):
	"""
	Test the TimedFile
	"""

	def set_up(self):
		"""
		Set up the test case
		"""

		super(TimedFileTest, self).set_up()

		self.prefix = helpers.random_str(20) + '.'
		self.directory = self.app.storage_path('logs')


	def tear_down(self):
		"""
		Tear down the test case
		"""

		super(TimedFileTest, self).tear_down()

		# Remove all profiler files
		directory = self.app.storage_path(self.directory)
		for root, subdirs, files in os.walk(directory):
			for file in files:
				if file.startswith(self.prefix):
					os.remove(os.path.join(root, file))


	def test_timed_file(self):
		"""
		Test the timed file
		"""

		info_string = 'info_%s' % helpers.random_str(20)
		warning_string = 'warning_%s' % helpers.random_str(20)
		error_string = 'error_%s' % helpers.random_str(20)

		# Write config
		self.write_test_config([
			"from Edmunds.Log.Drivers.TimedFile import TimedFile \n",
			"from logging import WARNING \n",
			"APP = { \n",
			"	'debug': False, \n",
			"	'log': { \n",
			"		'enabled': True, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'name': 'timedfile',\n",
			"				'driver': TimedFile,\n",
			"				'directory': '%s',\n" % self.directory,
			"				'prefix': '%s',\n" % self.prefix,
			"				'level': WARNING,\n"
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app and fetch stream
		app = self.create_application()
		self.assert_equal(self.directory, app.config('app.log.instances')[0]['directory'])
		directory = app.storage_path(self.directory)
		self.assert_equal(self.prefix, app.config('app.log.instances')[0]['prefix'])

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			app.logger.info(info_string)
			app.logger.warning(warning_string)
			app.logger.error(error_string)
			return ''

		with app.test_client() as c:

			# Check file
			self.assert_false(self._is_in_log_files(directory, info_string))
			self.assert_false(self._is_in_log_files(directory, warning_string))
			self.assert_false(self._is_in_log_files(directory, error_string))

			# Call route
			c.get(rule)

			# Check file
			self.assert_false(self._is_in_log_files(directory, info_string))
			self.assert_true(self._is_in_log_files(directory, warning_string))
			self.assert_true(self._is_in_log_files(directory, error_string))


	def _is_in_log_files(self, directory, string, starts_with = None):
		"""
		Check if string is in log files
		:param directory: 		The directory to check
		:type  directory:		str
		:param string: 			The string to check
		:type  string:			str
		:param starts_with: 	The filename starts with
		:type  starts_with:		str
		:return: 				Is in file
		:rtype:					boolean
		"""

		if starts_with is None:
			starts_with = self.prefix

		# Fetch files
		log_files = []
		for root, subdirs, files in os.walk(directory):
			for file in files:
				if file.startswith(starts_with):
					log_files.append(os.path.join(root, file))


		# Check files
		occurs = False
		for file in log_files:
			f = self.app.read_stream(file)

			try:
				if string in f.read():
					occurs = True
					break
			finally:
				f.close()

		return occurs