
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers
import os


class FileTest(TestCase):
	"""
	Test the File
	"""

	def set_up(self):
		"""
		Set up the test case
		"""

		super(FileTest, self).set_up()

		self.prefix = helpers.random_str(20) + '.'
		self.directory = os.path.join(os.sep, 'storage')
		self.clear_paths = []


	def tear_down(self):
		"""
		Tear down the test case
		"""

		super(FileTest, self).tear_down()

		# Remove all profiler files
		for directory in self.clear_paths:
			for root, subdirs, files in os.walk(directory):
				for file in files:
					if file.startswith(self.prefix):
						os.remove(os.path.join(root, file))


	def test_file(self):
		"""
		Test the file
		"""

		string = helpers.random_str(20)

		# Write config
		self.write_test_config([
			"from Edmunds.Storage.Drivers.File import File \n",
			"from logging import WARNING \n",
			"APP = { \n",
			"	'storage': { \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'name': 'file',\n",
			"				'driver': File,\n",
			"				'directory': '%s',\n" % self.directory,
			"				'prefix': '%s',\n" % self.prefix,
			"			}, \n",
			"		], \n",
			"	}, \n",
			"	'log': { \n",
			"		'instances': [ \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app
		app = self.create_application()
		directory = app.fs()._get_processed_path(None)
		self.clear_paths.append(directory)
		self.assert_equal(self.directory, app.config('app.storage.instances')[0]['directory'])
		self.assert_equal(self.prefix, app.config('app.storage.instances')[0]['prefix'])

		# Write
		stream = app.fs().write_stream('nice.txt')
		try:
			stream.write(string)
		finally:
			stream.close()

		# Read
		stream = app.fs().read_stream('nice.txt')
		try:
			self.assert_in(string, stream.read())
		finally:
			stream.close()

		# Copy
		self.assert_true(app.fs().copy('nice.txt', 'nice2.txt'))
		stream = app.fs().read_stream('nice2.txt')
		try:
			self.assert_in(string, stream.read())
		finally:
			stream.close()

		# Exists and delete
		self.assert_true(app.fs().exists('nice2.txt'))
		self.assert_true(app.fs().delete('nice2.txt'))
		self.assert_false(app.fs().exists('nice2.txt'))
