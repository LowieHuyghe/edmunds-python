
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers
import Edmunds.Application
import Edmunds.Support.ServiceProvider


class helpersTest(TestCase):
	"""
	Test the helpers
	"""

	def testGetClass(self):
		"""
		Test get_class
		"""

		data = (
			(TestCase, 'test.TestCase'),
			(Edmunds.Application.Application, 'Edmunds.Application'),
			(Edmunds.Support.ServiceProvider.ServiceProvider, 'Edmunds.Support.ServiceProvider'),
		)

		for test in data:
			assert test[0] == helpers.get_class(test[1])


	def testGetModuleAndClass(self):
		"""
		Test get_module_and_class
		"""

		data = (
			(('test.TestCase', 'TestCase'), 'test.TestCase'),
			(('Edmunds.Application', 'Application'), 'Edmunds.Application'),
			(('Edmunds.Support.ServiceProvider', 'ServiceProvider'), 'Edmunds.Support.ServiceProvider'),
		)

		for test in data:
			assert test[0] == helpers.get_module_and_class(test[1])


	def testGetFullClassName(self):
		"""
		Test get_full_class_name
		"""

		data = (
			('test.TestCase.TestCase', TestCase),
			('Edmunds.Application.Application', Edmunds.Application.Application),
			('Edmunds.Support.ServiceProvider.ServiceProvider', Edmunds.Support.ServiceProvider.ServiceProvider),
		)

		for test in data:
			assert test[0] == helpers.get_full_class_name(test[1])


	def testGetDirFromFile(self):
		"""
		Test get_dir_from_file
		"""

		data = (
			('/snape/kills', '/snape/kills/dumbledore.mp3'),
			('/ygritte/gets', '/ygritte/gets/killed.txt'),
			('/john/snow/rises/from/the', '/john/snow/rises/from/the/dead.py'),
			('/', '/whut.mov'),
		)

		for test in data:
			assert test[0] == helpers.get_dir_from_file(test[1])


	def testRandomStr(self):
		"""
		Test random_str
		"""

		# Test length
		assert 0 == len(helpers.random_str(0))
		assert 1 == len(helpers.random_str(1))
		assert 23 == len(helpers.random_str(23))
		assert 23 != len(helpers.random_str(32))

		# Test uniqueness
		assert helpers.random_str(0) == helpers.random_str(0)
		assert helpers.random_str(1) != helpers.random_str(1)
		assert helpers.random_str(23) != helpers.random_str(23)
		assert helpers.random_str(32) != helpers.random_str(32)
