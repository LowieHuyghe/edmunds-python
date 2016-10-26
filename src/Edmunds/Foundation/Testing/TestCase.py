
import unittest
from bootstrap import edmunds


class TestCase(unittest.TestCase):
	"""
	A UnitTest Test Case
	"""

	def setUp(self):
		"""
		Set up the test case
		"""
		self.app = self.createApplication()


	def tearDown(self):
		"""
		Tear down the test case
		"""
		pass


	def createApplication(self):
		"""
		Create the application for testing
		"""
		self.app = edmunds.bootstrap(__file__)
