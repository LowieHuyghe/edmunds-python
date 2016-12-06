
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers


class StreamTest(TestCase):
	"""
	Test the Stream
	"""

	def test_stream(self):
		"""
		Test the stream
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