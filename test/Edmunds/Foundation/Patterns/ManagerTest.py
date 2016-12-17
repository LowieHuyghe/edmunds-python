
from test.TestCase import TestCase
import Edmunds.Support.helpers as helpers

from Edmunds.Foundation.Patterns.Manager import Manager


class ManagerTest(TestCase):
	"""
	Test the Manager
	"""

	def set_up(self):
		"""
		Set up the test case
		"""

		super(ManagerTest, self).set_up()

		self._instances_config = [
			{
				'name': 'object',
				'driver': object,
				'object': 1,
			},
			{
				'name': 'dict',
				'driver': dict,
				'dict': 1,
			},
		]
		self._instances_config_extend = self._instances_config + [
			{
				'name': 'tuple',
				'driver': tuple,
				'tuple': 1,
			},
		]


	def test_get(self):
		"""
		Test the get method
		"""

		# Make manager
		manager = MyManager(self.app, self._instances_config)

		# Object config
		object_config = self._instances_config[0]
		object_return = manager.get(object_config['name'])

		# Check object config
		self.assert_is_not_none(object_return)
		self.assert_equal(2, len(object_return))
		self.assert_equal(object_config['name'], object_return[0])
		self.assert_equal(object_config['object'], object_return[1])

		# Dict config
		dict_config = self._instances_config[1]
		dict_return = manager.get(dict_config['name'])

		# Check dict config
		self.assert_is_not_none(dict_return)
		self.assert_equal(2, len(dict_return))
		self.assert_equal(dict_config['name'], dict_return[0])
		self.assert_equal(dict_config['dict'], dict_return[1])


	def test_all(self):
		"""
		Test the all method
		"""

		# Make manager
		manager = MyManager(self.app, self._instances_config)

		# Returns
		instances_return = manager.all()

		# Object config
		object_config = self._instances_config[0]
		object_return = instances_return[0]

		# Check object config
		self.assert_is_not_none(object_return)
		self.assert_equal(2, len(object_return))
		self.assert_equal(object_config['name'], object_return[0])
		self.assert_equal(object_config['object'], object_return[1])

		# Dict config
		dict_config = self._instances_config[1]
		dict_return = instances_return[1]

		# Check dict config
		self.assert_is_not_none(dict_return)
		self.assert_equal(2, len(dict_return))
		self.assert_equal(dict_config['name'], dict_return[0])
		self.assert_equal(dict_config['dict'], dict_return[1])


	def test_extend(self):
		"""
		Test the extend method
		"""

		# Make manager
		manager = MyManager(self.app, self._instances_config_extend)

		# Test error
		with self.assert_raises_regexp(AttributeError, '_create_tuple'):
			manager.all()

		# Extend
		extend_lambda = lambda config: (config['name'], config['tuple'])
		manager.extend(tuple, extend_lambda)

		# Check get
		tuple_config = self._instances_config_extend[2]
		tuple_return = manager.get(tuple_config['name'])

		self.assert_is_not_none(tuple_return)
		self.assert_equal(2, len(tuple_return))
		self.assert_equal(tuple_config['name'], tuple_return[0])
		self.assert_equal(tuple_config['tuple'], tuple_return[1])

		# Check all
		tuple_config = self._instances_config_extend[2]
		tuple_return = manager.all()[2]

		self.assert_is_not_none(tuple_return)
		self.assert_equal(2, len(tuple_return))
		self.assert_equal(tuple_config['name'], tuple_return[0])
		self.assert_equal(tuple_config['tuple'], tuple_return[1])



class MyManager(Manager):

	def _create_object(self, config):

		return (config['name'], config['object'])

	def _create_dict(self, config):

		return (config['name'], config['dict'])