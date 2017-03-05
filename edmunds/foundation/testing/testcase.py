
import abc
import os
import threading
import time
import unittest
ABC = abc.ABCMeta('ABC', (object,), {})


class TestCase(unittest.TestCase, ABC):
	"""
	A UnitTest Test Case
	"""

	def set_up(self):
		"""
		Set up the test case
		"""

		# Create the application
		if not hasattr(self, 'app'):
			self.app = self.create_application()

		# The env testing test file
		self.env_testing_test_file = os.path.join(self.app.config.root_path, '.env.testing.test.py')
		if os.path.exists(self.env_testing_test_file):
			os.remove(self.env_testing_test_file)

			self.app = self.create_application()


	def tear_down(self):
		"""
		Tear down the test case
		"""

		# Remove env-testing-file
		if os.path.exists(self.env_testing_test_file):
			os.remove(self.env_testing_test_file)


	@abc.abstractmethod
	def create_application(self):
		"""
		Create the application for testing
		"""
		pass


	def write_config(self, config, overwrite = True):
		"""
		Write to test config file
		:param config: 		The config to write
		:type  config:		str|list
		:param overwrite:	Overwrite the current config
		:type  overwrite:	bool
		"""

		if not os.path.exists(self.env_testing_test_file):
			overwrite = True

		write_permissions = 'w' if overwrite else 'w+'
		with open(self.env_testing_test_file, write_permissions) as f:
			f.write('\n')
			if isinstance(config, list):
				f.writelines(config)
			else:
				f.write(config)
			f.write('\n')


	def thread(self, target, count = 1000):
		"""
		Test thread safety of function
		:param target:	The target function
		:type  target:	Callable
		:param count:	The call count
		:type  count:	int
		"""

		for _ in self.thread_iter(target, count):
			pass


	def thread_iter(self, target, count = 1000):
		"""
		Test thread safety of function
		:param target:	The target function
		:type  target:	Callable
		:param count:	The call count
		:type  count:	int
		"""

		threads = {}

		# Make all the threads
		for index in range(count):
			threads[index] = threading.Thread(target = target)

		# Start all the thread (for minimun delay this is done after constructing the threads)
		for index in threads:
			threads[index].start()

		# Wait for each thread to finish
		while len(threads) > 0:
			for index in threads.keys():
				if not threads[index].isAlive():
					yield index
					del threads[index]

			time.sleep(0.01)


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

	def skip(self, reason):
		"""
		Skip this test
		"""
		return self.skipTest(reason)

