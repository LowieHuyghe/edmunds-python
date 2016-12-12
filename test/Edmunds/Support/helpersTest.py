
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers
import Edmunds.Application
import Edmunds.Support.ServiceProvider


class helpersTest(TestCase):
	"""
	Test the helpers
	"""

	def test_get_class(self):
		"""
		Test get_class
		"""

		data = (
			(TestCase, 'test.TestCase'),
			(Edmunds.Application.Application, 'Edmunds.Application'),
			(Edmunds.Support.ServiceProvider.ServiceProvider, 'Edmunds.Support.ServiceProvider'),
		)

		for test in data:
			self.assert_equal(test[0], helpers.get_class(test[1]))


	def test_get_module_and_class(self):
		"""
		Test get_module_and_class
		"""

		data = (
			(('test.TestCase', 'TestCase'), 'test.TestCase'),
			(('Edmunds.Application', 'Application'), 'Edmunds.Application'),
			(('Edmunds.Support.ServiceProvider', 'ServiceProvider'), 'Edmunds.Support.ServiceProvider'),
		)

		for test in data:
			self.assert_equal(test[0], helpers.get_module_and_class(test[1]))


	def test_get_full_class_name(self):
		"""
		Test get_full_class_name
		"""

		data = (
			('test.TestCase.TestCase', TestCase),
			('Edmunds.Application.Application', Edmunds.Application.Application),
			('Edmunds.Support.ServiceProvider.ServiceProvider', Edmunds.Support.ServiceProvider.ServiceProvider),
		)

		for test in data:
			self.assert_equal(test[0], helpers.get_full_class_name(test[1]))


	def test_get_dir_from_file(self):
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
			self.assert_equal(test[0], helpers.get_dir_from_file(test[1]))


	def test_random_str(self):
		"""
		Test random_str
		"""

		# Test length
		self.assert_equal(0, len(helpers.random_str(0)))
		self.assert_equal(7, len(helpers.random_str(7)))
		self.assert_equal(23, len(helpers.random_str(23)))
		self.assert_not_equal(23, len(helpers.random_str(32)))

		# Test uniqueness
		self.assert_equal(helpers.random_str(0), helpers.random_str(0))
		self.assert_not_equal(helpers.random_str(7), helpers.random_str(7))
		self.assert_not_equal(helpers.random_str(23), helpers.random_str(23))
		self.assert_not_equal(helpers.random_str(32), helpers.random_str(32))


	def test_snake_case(self):
		"""
		Test snake case
		"""

		data = (
			('CamelCase', 				'camel_case'),
			('CamelCamelCase', 			'camel_camel_case'),
			('Camel2Camel2Case', 		'camel2_camel2_case'),
			('getHTTPResponseCode', 	'get_http_response_code'),
			('get2HTTPResponseCode', 	'get2_http_response_code'),
			('HTTPResponseCode', 		'http_response_code'),
			('HTTPResponseCodeXYZ', 	'http_response_code_xyz'),
		)

		for test in data:
			self.assert_equal(test[1], helpers.snake_case(test[0]))