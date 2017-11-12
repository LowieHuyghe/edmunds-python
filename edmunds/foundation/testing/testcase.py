
import os
import sys
import threading
import time
import unittest
from edmunds.globals import abc, ABC
import edmunds.support.helpers as helpers
import tempfile
import shutil


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

        # Temp dirs and files
        self._temp_files = []
        self._temp_dirs = []

    def tear_down(self):
        """
        Tear down the test case
        """

        # Remove env-testing-file
        if os.path.exists(self.env_testing_test_file):
            os.remove(self.env_testing_test_file)

        # Clean temp files and dirs
        for temp_file in self._temp_files:
            if os.path.isfile(temp_file):
                os.remove(temp_file)
        for temp_dir in self._temp_dirs:
            if os.path.isdir(temp_dir):
                shutil.rmtree(temp_dir)

    @abc.abstractmethod
    def create_application(self):
        """
        Create the application for testing
        :return:    Application
        :rtype:     edmunds.application.Application
        """
        pass

    def write_config(self, config, overwrite=True):
        """
        Write to test config file
        :param config:      The config to write
        :type  config:      str|list
        :param overwrite:   Overwrite the current config
        :type  overwrite:   bool
        :return:            The file written to
        :rtype:             str
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

        return self.env_testing_test_file

    def thread(self, target, count=1000):
        """
        Test thread safety of function
        :param target:  The target function
        :type  target:  Callable
        :param count:   The call count
        :type  count:   int
        """

        for _ in self.thread_iter(target, count):
            pass

    def thread_iter(self, target, count=1000):
        """
        Test thread safety of function
        :param target:  The target function
        :type  target:  Callable
        :param count:   The call count
        :type  count:   int
        """

        threads = {}

        # Make all the threads
        for index in range(count):
            threads[index] = threading.Thread(target=target)

        # Start all the thread (for minimun delay this is done after constructing the threads)
        for index in threads:
            threads[index].start()

        # Wait for each thread to finish
        while len(threads) > 0:
            for index in list(threads.keys()):
                if not threads[index].isAlive():
                    yield index
                    del threads[index]

            time.sleep(0.01)

    def rand_str(self, length=20):
        """
        Get random string of certain length
        :param length:  The length of the string
        :return:        Random string
        """

        return helpers.random_str(length)

    def rand_int(self, min, max):
        """
        Get random integer
        :param min: Minimum value (included)
        :param max: Maximum value (included)
        :return:    Random integer
        """

        return helpers.random_int(min, max)

    def temp_file(self, only_path=False, suffix='', prefix='tmp'):
        """
        Get temp file
        :param only_path:   Only return the path
        :param suffix:      File suffix
        :param prefix:      File prefix
        :return:            Path to temp file
        """

        path = tempfile.mktemp(suffix=suffix, prefix=prefix)
        if only_path and os.path.isfile(path):
            os.remove(path)
        self._temp_files.append(path)
        return path

    def write_temp_file(self, content, suffix='', prefix='tmp'):
        """
        Write to temp file
        :param content: The content to write to file
        :param suffix:  File suffix
        :param prefix:  File prefix
        :return:        The file path
        """

        path = self.temp_file(suffix=suffix, prefix=prefix)

        with open(path, 'w') as file:
            file.write(content)

        return path

    def temp_dir(self, only_path=False, suffix='', prefix='tmp'):
        """
        Get temp dir
        :param only_path:   Only return the path
        :param suffix:      File suffix
        :param prefix:      File prefix
        :return:            Path to temp dir
        """

        path = tempfile.mkdtemp(suffix=suffix, prefix=prefix)
        if only_path and os.path.isdir(path):
            shutil.rmtree(path)
        self._temp_dirs.append(path)
        return path

    def directory(self):
        """
        Get the tests directory
        :return:    Directory
        """

        current_dir = os.path.dirname(os.path.abspath(__file__))
        tests_dir = os.path.join(current_dir, os.pardir, os.pardir, os.pardir, 'tests')
        tests_dir = os.path.abspath(tests_dir)
        return tests_dir

    def setUp(self):
        self.set_up()

    def tearDown(self):
        self.tear_down()

    def assert_equal(self, a, b, msg=None):
        """
        a == b
        """
        return self.assertEqual(a, b, msg=msg)

    def assert_not_equal(self, a, b, msg=None):
        """
        a != b
        """
        return self.assertNotEqual(a, b, msg=msg)

    def assert_true(self, x, msg=None):
        """
        bool(x) is True
        """
        return self.assertTrue(x, msg=msg)

    def assert_false(self, x, msg=None):
        """
        bool(x) is False
        """
        return self.assertFalse(x, msg=msg)

    def assert_is(self, a, b, msg=None):
        """
        a is b
        """
        return self.assertIs(a, b, msg=msg)

    def assert_is_not(self, a, b, msg=None):
        """
        a is not b
        """
        return self.assertIsNot(a, b, msg=msg)

    def assert_is_none(self, x, msg=None):
        """
        x is None
        """
        return self.assertIsNone(x, msg=msg)

    def assert_is_not_none(self, x, msg=None):
        """
        x is not None
        """
        return self.assertIsNotNone(x, msg=msg)

    def assert_in(self, a, b, msg=None):
        """
        a in b
        """
        return self.assertIn(a, b, msg=msg)

    def assert_not_in(self, a, b, msg=None):
        """
        a not in b
        """
        return self.assertNotIn(a, b, msg=msg)

    def assert_is_instance(self, a, b, msg=None):
        """
        isinstance(a, b)
        """
        return self.assertIsInstance(a, b, msg=msg)

    def assert_not_is_instance(self, a, b, msg=None):
        """
        not isinstance(a, b)
        """
        return self.assertNotIsInstance(a, b, msg=msg)

    def assert_raises(self, exc, *args, **kwds):
        """
        fun(*args, **kwds) raises exc
        """
        return self.assertRaises(exc, *args, **kwds)

    def assert_raises_regexp(self, exc, r, *args, **kwds):
        """
        fun(*args, **kwds) raises exc and the message matches regex r
        """
        if sys.version_info >= (3, 0):
            return self.assertRaisesRegex(exc, r, *args, **kwds)
        return self.assertRaisesRegexp(exc, r, *args, **kwds)

    def assert_almost_equal(self, a, b, msg=None):
        """
        round(a-b, 7) == 0
        """
        return self.assertAlmostEqual(a, b, msg=msg)

    def assert_not_almost_equal(self, a, b, msg=None):
        """
        round(a-b, 7) != 0
        """
        return self.assertNotAlmostEqual(a, b, msg=msg)

    def assert_greater(self, a, b, msg=None):
        """
        a > b
        """
        return self.assertGreater(a, b, msg=msg)

    def assert_greater_equal(self, a, b, msg=None):
        """
        a >= b
        """
        return self.assertGreaterEqual(a, b, msg=msg)

    def assert_less(self, a, b, msg=None):
        """
        a < b
        """
        return self.assertLess(a, b, msg=msg)

    def assert_less_equal(self, a, b, msg=None):
        """
        a <= b
        """
        return self.assertLessEqual(a, b, msg=msg)

    def assert_regexp_matches(self, s, r, msg=None):
        """
        r.search(s)
        """
        if sys.version_info >= (3, 0):
            return self.assertRegex(s, r, msg=msg)
        return self.assertRegexpMatches(s, r, msg=msg)

    def assert_not_regexp_matches(self, s, r, msg=None):
        """
        not r.search(s)
        """
        if sys.version_info >= (3, 0):
            return self.assertNotRegex(s, r, msg=msg)
        return self.assertNotRegexpMatches(s, r, msg=msg)

    def assert_multi_line_equal(self, a, b, msg=None):
        """
        strings
        """
        return self.assertMultiLineEqual(a, b, msg=msg)

    def assert_sequence_equal(self, a, b, msg=None):
        """
        sequences
        """
        return self.assertSequenceEqual(a, b, msg=msg)

    def assert_list_equal(self, a, b, msg=None):
        """
        lists
        """
        return self.assertListEqual(a, b, msg=msg)

    def assert_tuple_equal(self, a, b, msg=None):
        """
        tuples
        """
        return self.assertTupleEqual(a, b, msg=msg)

    def assert_dict_equal(self, a, b, msg=None):
        """
        dicts
        """
        return self.assertDictEqual(a, b, msg=msg)

    def skip(self, reason):
        """
        Skip this test
        """
        return self.skipTest(reason)

    def assert_equal_deep(self, expected, value, check_type=True, msg=None):
        """
        Assert equal deep
        :param expected:    The expected value
        :param value:       The value
        :param check_type:  Do type check
        :return:
        """

        if isinstance(expected, dict):
            self.assert_is_instance(value, dict, msg=msg)

            for i in range(0, len(expected)):
                self.assert_equal_deep(sorted(expected)[i], sorted(value)[i], check_type=check_type, msg=msg)
                self.assert_equal_deep(expected[sorted(expected)[i]], value[sorted(value)[i]], check_type=check_type, msg=msg)

        elif isinstance(expected, list):
            self.assert_is_instance(value, list, msg=msg)

            for i in range(0, len(expected)):
                self.assert_equal_deep(expected[i], value[i], check_type=check_type, msg=msg)

        else:
            self.assert_equal(expected, value, msg=msg)
            if check_type:
                self.assert_is_instance(value, type(expected), msg=msg)
