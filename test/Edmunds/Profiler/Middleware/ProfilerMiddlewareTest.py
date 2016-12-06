
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers


class ProfilerMiddlewareTest(TestCase):
	"""
	Test the Profiler Middleware
	"""

	def test_profiler_disabled(self):
		"""
		Test profiler disabled
		"""

		# Write config
		self.write_test_config([
			"from Edmunds.Profiler.Drivers.Stream import Stream \n",
			"import cStringIO \n",
			"APP = { \n",
			"	'debug': True, \n",
			"	'profiler': { \n",
			"		'enabled': False, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app and fetch stream
		app = self.create_application()
		stream = app.config('app.profiler.instances')[0]['stream']

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			return ''

		with app.test_client() as c:

			# Check stream
			self.assert_equal('', stream.getvalue())

			# Call route
			c.get(rule)

			# Check stream
			self.assert_equal('', stream.getvalue())


	def test_app_no_debug(self):
		"""
		Test app no debug
		"""

		# Write config
		self.write_test_config([
			"from Edmunds.Profiler.Drivers.Stream import Stream \n",
			"import cStringIO \n",
			"APP = { \n",
			"	'debug': False, \n",
			"	'profiler': { \n",
			"		'enabled': True, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app and fetch stream
		app = self.create_application()
		stream = app.config('app.profiler.instances')[0]['stream']

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			return ''

		with app.test_client() as c:

			# Check stream
			self.assert_equal('', stream.getvalue())

			# Call route
			c.get(rule)

			# Check stream
			self.assert_equal('', stream.getvalue())


	def test_app_no_debug_profiler_disabled(self):
		"""
		Test app no debug and profiler disabled
		"""

		# Write config
		self.write_test_config([
			"from Edmunds.Profiler.Drivers.Stream import Stream \n",
			"import cStringIO \n",
			"APP = { \n",
			"	'debug': False, \n",
			"	'profiler': { \n",
			"		'enabled': False, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app and fetch stream
		app = self.create_application()
		stream = app.config('app.profiler.instances')[0]['stream']

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			return ''

		with app.test_client() as c:

			# Check stream
			self.assert_equal('', stream.getvalue())

			# Call route
			c.get(rule)

			# Check stream
			self.assert_equal('', stream.getvalue())


	def test_enabled(self):
		"""
		Test enabled
		"""

		# Write config
		self.write_test_config([
			"from Edmunds.Profiler.Drivers.Stream import Stream \n",
			"import cStringIO \n",
			"APP = { \n",
			"	'debug': True, \n",
			"	'profiler': { \n",
			"		'enabled': True, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app and fetch stream
		app = self.create_application()
		stream = app.config('app.profiler.instances')[0]['stream']

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			return ''

		with app.test_client() as c:

			# Check stream
			self.assert_equal('', stream.getvalue())

			# Call route
			c.get(rule)

			# Check stream
			self.assert_not_equal('', stream.getvalue())


	def test_multiple_profilers(self):
		"""
		Test multiple profilers
		"""

		# Write config
		self.write_test_config([
			"from Edmunds.Profiler.Drivers.Stream import Stream \n",
			"import cStringIO \n",
			"APP = { \n",
			"	'debug': True, \n",
			"	'profiler': { \n",
			"		'enabled': True, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"			{ \n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app and fetch stream
		app = self.create_application()
		stream = app.config('app.profiler.instances')[0]['stream']
		stream2 = app.config('app.profiler.instances')[1]['stream']

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			return ''

		with app.test_client() as c:

			# Check stream
			self.assert_equal('', stream.getvalue())
			self.assert_equal('', stream2.getvalue())
			self.assert_equal(stream.getvalue(), stream2.getvalue())

			# Call route
			c.get(rule)

			# Check stream
			self.assert_not_equal('', stream.getvalue())
			self.assert_not_equal('', stream2.getvalue())
			self.assert_equal(stream.getvalue(), stream2.getvalue())