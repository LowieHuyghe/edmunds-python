
from test.TestCase import TestCase
import edmunds.support.helpers as helpers
import os


class TestLogServiceProvider(TestCase):
	"""
	Test the Log Service Provider
	"""

	def test_logging_disabled(self):
		"""
		Test logging disabled
		"""

		log_string = 'LogServiceProviderTest::test_logging_disabled'

		# Write config
		self.write_test_config([
			"from Edmunds.Log.Drivers.Stream import Stream \n",
			"import cStringIO \n",
			"APP = { \n",
			"	'debug': False, \n",
			"	'log': { \n",
			"		'enabled': False, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'name': 'stream',\n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app
		app = self.create_application()
		stream = app.config('app.log.instances')[0]['stream']

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			app.logger.error(log_string)
			return ''

		with app.test_client() as c:

			# Check log files
			self.assert_not_in(log_string, stream.getvalue())

			# Call route
			c.get(rule)

			# Check log files
			self.assert_not_in(log_string, stream.getvalue())


	def test_logging_enabled(self):
		"""
		Test logging enabled
		"""

		log_string = 'LogServiceProviderTest::test_logging_enabled'

		# Write config
		self.write_test_config([
			"from Edmunds.Log.Drivers.Stream import Stream \n",
			"import cStringIO \n",
			"APP = { \n",
			"	'debug': False, \n",
			"	'log': { \n",
			"		'enabled': True, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'name': 'stream',\n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app
		app = self.create_application()
		stream = app.config('app.log.instances')[0]['stream']

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			app.logger.error(log_string)
			return ''

		with app.test_client() as c:

			# Check log files
			self.assert_not_in(log_string, stream.getvalue())

			# Call route
			c.get(rule)

			# Check log files
			self.assert_in(log_string, stream.getvalue())


	def test_multiple_loggers(self):
		"""
		Test logging enabled
		"""

		log_string = 'LogServiceProviderTest::test_logging_enabled'

		# Write config
		self.write_test_config([
			"from Edmunds.Log.Drivers.Stream import Stream \n",
			"import cStringIO \n",
			"APP = { \n",
			"	'debug': False, \n",
			"	'log': { \n",
			"		'enabled': True, \n",
			"		'instances': [ \n",
			"			{ \n",
			"				'name': 'stream',\n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"			{ \n",
			"				'name': 'stream2',\n",
			"				'driver': Stream,\n",
			"				'stream': cStringIO.StringIO(),\n",
			"			}, \n",
			"		], \n",
			"	}, \n",
			"} \n",
		])

		# Create app
		app = self.create_application()
		stream = app.config('app.log.instances')[0]['stream']
		stream2 = app.config('app.log.instances')[1]['stream']

		# Add route
		rule = '/' + helpers.random_str(20)
		@app.route(rule)
		def handleRoute():
			app.logger.error(log_string)
			return ''

		with app.test_client() as c:

			# Check log files
			self.assert_not_in(log_string, stream.getvalue())
			self.assert_not_in(log_string, stream2.getvalue())

			# Call route
			c.get(rule)

			# Check log files
			self.assert_in(log_string, stream.getvalue())
			self.assert_in(log_string, stream2.getvalue())
