
import unittest
from bootstrap import edmunds
import abc


class TestCase(unittest.TestCase):
	"""
	A UnitTest Test Case
	"""

	__metaclass__ = abc.ABCMeta


	def setUp(self):
		"""
		Set up the test case
		"""
		if not hasattr(self, 'app'):
			self.app = self.createApplication()


	def tearDown(self):
		"""
		Tear down the test case
		"""
		pass


	@abc.abstractmethod
	def createApplication(self):
		"""
		Create the application for testing
		"""
		pass
