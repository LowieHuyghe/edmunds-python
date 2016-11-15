
import unittest
from bootstrap import edmunds
import abc


class TestCase(unittest.TestCase):
	"""
	A UnitTest Test Case
	"""

	__metaclass__ = abc.ABCMeta


	def set_up(self):
		"""
		Set up the test case
		"""
		if not hasattr(self, 'app'):
			self.app = self.create_application()


	def tear_down(self):
		"""
		Tear down the test case
		"""
		pass


	@abc.abstractmethod
	def create_application(self):
		"""
		Create the application for testing
		"""
		pass


	def setUp(self):
		self.set_up()

	def tearDown(self):
		self.tear_down()

	def assert_equal(self, a, b):
		"""
		a == b
		"""
		return self.assertEqual(a, b)

	def assert_not_equal(self, a, b):
		"""
		a != b
		"""
		return self.assertNotEqual(a, b)

	def assert_true(self, x):
		"""
		bool(x) is True
		"""
		return self.assertTrue(x)

	def assert_false(self, x):
		"""
		bool(x) is False
		"""
		return self.assertFalse(x)

	def assert_is(self, a, b):
		"""
		a is b
		"""
		return self.assertIs(a, b)

	def assert_is_not(self, a, b):
		"""
		a is not b
		"""
		return self.assertIsNot(a, b)

	def assert_is_none(self, x):
		"""
		x is None
		"""
		return self.assertIsNone(x)

	def assert_is_not_none(self, x):
		"""
		x is not None
		"""
		return self.assertIsNotNone(x)

	def assert_in(self, a, b):
		"""
		a in b
		"""
		return self.assertIn(a, b)

	def assert_not_in(self, a, b):
		"""
		a not in b
		"""
		return self.assertNotIn(a, b)

	def assert_is_instance(self, a, b):
		"""
		isinstance(a, b)
		"""
		return self.assertIsInstance(a, b)

	def assert_not_is_instance(self, a, b):
		"""
		not isinstance(a, b)
		"""
		return self.assertNotIsInstance(a, b)

	def assert_raises(self, exc, *args, **kwds):
		"""
		fun(*args, **kwds) raises exc
		"""
		return self.assertRaises(exc, *args, **kwds)

	def assert_raises_regexp(self, exc, r, *args, **kwds):
		"""
		fun(*args, **kwds) raises exc and the message matches regex r
		"""
		return self.assertRaisesRegexp(exc, r, *args, **kwds)

	def assert_almost_equal(self, a, b):
		"""
		round(a-b, 7) == 0
		"""
		return self.assertAlmostEqual(a, b)

	def assert_not_almost_equal(self, a, b):
		"""
		round(a-b, 7) != 0
		"""
		return self.assertNotAlmostEqual(a, b)

	def assert_greater(self, a, b):
		"""
		a > b
		"""
		return self.assertGreater(a, b)

	def assert_greater_equal(self, a, b):
		"""
		a >= b
		"""
		return self.assertGreaterEqual(a, b)

	def assert_less(self, a, b):
		"""
		a < b
		"""
		return self.assertLess(a, b)

	def assert_less_equal(self, a, b):
		"""
		a <= b
		"""
		return self.assertLessEqual(a, b)

	def assert_regexp_matches(self, s, r):
		"""
		r.search(s)
		"""
		return self.assertRegexpMatches(s, r)

	def assert_not_regexp_matches(self, s, r):
		"""
		not r.search(s)
		"""
		return self.assertNotRegexpMatches(s, r)

	def assert_items_equal(self, a, b):
		"""
		sorted(a) == sorted(b) and works with unhashable objs
		"""
		return self.assertItemsEqual(a, b)

	def assert_dict_contains_subset(self, a, b):
		"""
		all the key/value pairs in a exist in b
		"""
		return self.assertDictContainsSubset(a, b)

	def assert_multi_line_equal(self, a, b):
		"""
		strings
		"""
		return self.assertMultiLineEqual(a, b)

	def assert_sequence_equal(self, a, b):
		"""
		sequences
		"""
		return self.assertSequenceEqual(a, b)

	def assert_list_equal(self, a, b):
		"""
		lists
		"""
		return self.assertListEqual(a, b)

	def assert_tuple_equal(self, a, b):
		"""
		tuples
		"""
		return self.assertTupleEqual(a, b)

	def assert_set_equal(self, a, b):
		"""
		sets or frozensets
		"""
		return self.assertSetEqual(a, b)

	def assert_dict_equal(self, a, b):
		"""
		dicts
		"""
		return self.assertDictEqual(a, b)

