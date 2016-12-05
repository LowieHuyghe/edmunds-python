
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers


class StreamTest(TestCase):
	"""
	Test the Stream
	"""

	def set_up(self):
		"""
		Set up the test case
		"""

		super(StreamTest, self).set_up()

		self.write_test_config([
			"from Edmunds.Profiler.Drivers.Stream import Stream \n",
			"import cStringIO \n",
			"APP = { \n",
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

		self.app = self.create_application()
		self.stream = self.app.config('app.profiler.instances')[0]['stream']

		# Check stream
		self.assert_is_not_none(self.stream)


	def test_stream(self):
		"""
		Test the stream
		"""

		rule = '/' + helpers.random_str(20)

		# Add route
		@self.app.route(rule)
		def handleRoute():
			return ''

		with self.app.test_client() as c:

			# Check stream
			self.assert_equal('', self.stream.getvalue())

			# Call route
			c.get(rule)

			# Check stream
			self.assert_not_equal('', self.stream.getvalue())