
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers
import os


class CallGraphTest(TestCase):
	"""
	Test the CallGraph
	"""

	def set_up(self):
		"""
		Set up the test case
		"""

		super(CallGraphTest, self).set_up()

		self.prefix = helpers.random_str(20) + '.'
		self.directory = os.path.join(os.sep, 'profs')
		self.clear_paths = []


	def tear_down(self):
		"""
		Tear down the test case
		"""

		super(CallGraphTest, self).tear_down()

		# Remove all profiler files
		for directory in self.clear_paths:
			for root, subdirs, files in os.walk(directory):
				for file in files:
					if file.startswith(self.prefix):
						os.remove(os.path.join(root, file))


	def test_callgraph(self):
		"""
		Test the callgraph
		"""

		# Write config
		self.write_test_config([
			"from Edmunds.Profiler.Drivers.CallGraph import CallGraph \n",
			"import cStringIO \n",
			"APP = { \n",
			"	'debug': True, \n",
			"	'profiler': { \n",
			"		'enabled': True, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'name': 'callgraph',\n",
			"				'driver': CallGraph,\n",
			"				'directory': '%s',\n" % self.directory,
			"				'prefix': '%s',\n" % self.prefix,
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app and fetch stream
		app = self.create_application()
		directory = app.fs()._get_processed_path(self.directory)
		self.clear_paths.append(directory)
		self.assert_equal(self.directory, app.config('app.profiler.instances')[0]['directory'])
		self.assert_equal(self.prefix, app.config('app.profiler.instances')[0]['prefix'])

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			return ''

		with app.test_client() as c:

			# Count profiler files
			prof_files = []
			for root, subdirs, files in os.walk(directory):
				for file in files:
					if file.startswith(self.prefix):
						prof_files.append(os.path.join(root, file))

			self.assert_equal(0, len(prof_files))

			# Call route
			c.get(rule)

			# Count profiler files
			prof_files = []
			for root, subdirs, files in os.walk(directory):
				for file in files:
					if file.startswith(self.prefix):
						prof_files.append(os.path.join(root, file))

			self.assert_equal(1, len(prof_files))