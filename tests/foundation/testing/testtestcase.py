
from tests.testcase import TestCase
import unittest
import sys
import os


class TestTestCase(unittest.TestCase):

    def setUp(self):
        """
        Set up the test
        :return:    void
        """

        self._testCase = TestCase(methodName='assertEqual')
        self._testCase.set_up()

    def tearDown(self):
        """
        Tear down the test
        :return:    void
        """

        self._testCase.tear_down()

    def test_compare_methods(self):
        """
        Test the compare methods
        :return:    void
        """

        methods = [
            (self.assertEqual,          self._testCase.assert_equal,            2,  ([], [])),
            (self.assertNotEqual,       self._testCase.assert_not_equal,        2,  ([], [])),
            (self.assertTrue,           self._testCase.assert_true,             1,  []),
            (self.assertFalse,          self._testCase.assert_false,            1,  []),
            (self.assertIs,             self._testCase.assert_is,               2,  ([], [])),
            (self.assertIsNot,          self._testCase.assert_is_not,           2,  ([], [])),
            (self.assertIsNone,         self._testCase.assert_is_none,          1,  []),
            (self.assertIsNotNone,      self._testCase.assert_is_not_none,      1,  []),
            (self.assertIn,             self._testCase.assert_in,               2,  ([], [list])),
            (self.assertNotIn,          self._testCase.assert_not_in,           2,  ([], [list])),
            (self.assertAlmostEqual,    self._testCase.assert_almost_equal,     2,  ([float, int], [float, int])),
            (self.assertNotAlmostEqual, self._testCase.assert_not_almost_equal, 2,  ([float, int], [float, int])),
            (self.assertGreater,        self._testCase.assert_greater,          2,  ([float, int], [float, int])),
            (self.assertGreaterEqual,   self._testCase.assert_greater_equal,    2,  ([float, int], [float, int])),
            (self.assertLess,           self._testCase.assert_less,             2,  ([float, int], [float, int])),
            (self.assertLessEqual,      self._testCase.assert_less_equal,       2,  ([float, int], [float, int])),
            (self.assertIsInstance,     self._testCase.assert_is_instance,      2,  ([], [type])),
            (self.assertNotIsInstance,  self._testCase.assert_not_is_instance,  2,  ([], [type])),
            (self.assertDictEqual,      self._testCase.assert_dict_equal,       2,  ([dict], [dict])),
            (self.assertListEqual,      self._testCase.assert_list_equal,       2,  ([list], [list])),
            (self.assertTupleEqual,     self._testCase.assert_tuple_equal,      2,  ([tuple], [tuple])),
            (self.assertSequenceEqual,  self._testCase.assert_sequence_equal,   2,  ([tuple, list, str], [tuple, list, str])),
            (self.assertMultiLineEqual, self._testCase.assert_multi_line_equal, 2,  ([str], [str])),
        ]
        values = [
            0, 1, 2, 3, 4, 12, 23, 42, 2455, 19204, 34353589, 34893597587,
            0.0, 1.1, 2.2, 3.3, 4.4, 2355.353, 353.54, 59035890.45, 53509804.434434,
            True,
            False,
            '', 'a', 'b', 'c', 'this is', 'a test', 'I\'m sorry, Dave', 'WhatANiceSentenceThisIs',
            '\n', 'May the odds be\never in your favor.', 'Stacy\'s mom has\ngot it goin on.',
            (), (1, 2, 3, 34343), ('', 'a', 'B', 'Wow wow wow'), (23, 'NaN', 'We had a doozy of a day.', 83498.38498),
            [], [343, 4, 5509], ['', 'Hydrate!', 'You can\'t handle the juice'], [343, 'Another value', 343.34444444],
            {}, {1: 23, 345: 'Bird is the word'}, {'Chicken': 'Fillet'}, {'Answer': 42},
            int, float, list, tuple, dict, str
        ]

        for method_original, method_test_case, arguments, types in methods:
            for value in values:
                if arguments == 1:
                    if types and type(value) not in types:
                        continue
                    success_original = self._get_assertion_success(method_original, [value])
                    success_test_case = self._get_assertion_success(method_test_case, [value])
                    self.assertEqual(success_original, success_test_case)
                else:
                    if types[0] and type(value) not in types[0]:
                        continue
                    for value2 in values:
                        if types[1] and type(value2) not in types[1]:
                            continue
                        success_original = self._get_assertion_success(method_original, [value, value2])
                        success_test_case = self._get_assertion_success(method_test_case, [value, value2])
                        self.assertEqual(success_original, success_test_case)

    def test_regex_methods(self):
        """
        Test regex methods
        :return:    void
        """

        values = [
            '', 'a', 'b', 'c', 'this is', 'a test', 'I\'m sorry, Dave', 'WhatANiceSentenceThisIs',
            '\n', 'May the odds be\never in your favor.', 'Stacy\'s mom has\ngot it goin on.',
        ]
        regexes = [
            '', '/', '.', '$', '^', '\s+', '\S+', '\d+', '\D+', '\/',
        ]

        for value in values:
            for regex in regexes:
                if sys.version_info >= (3, 0):
                    success_original = self._get_assertion_success(self.assertRegex, [value, regex])
                else:
                    success_original = self._get_assertion_success(self.assertRegexpMatches, [value, regex])
                success_test_case = self._get_assertion_success(self._testCase.assert_regexp_matches, [value, regex])
                self.assertEqual(success_original, success_test_case)

                if sys.version_info >= (3, 0):
                    success_original = self._get_assertion_success(self.assertNotRegex, [value, regex])
                else:
                    success_original = self._get_assertion_success(self.assertNotRegexpMatches, [value, regex])
                success_test_case = self._get_assertion_success(self._testCase.assert_not_regexp_matches, [value, regex])
                self.assertEqual(success_original, success_test_case)

    def test_raises_methods(self):
        """
        Test raises methods
        :return:    void
        """

        def raises_error():
            raise RuntimeError('This is a runtime-error')

        def raises_nothing():
            pass

        functions = [
            raises_error,
            raises_nothing
        ]
        regexes = [
            '', '/', '.', '$', '^', '\s+', '\S+', '\d+', '\D+', '\/',
        ]
        errors = [
            RuntimeError,
            SystemError,
            SystemExit,
            KeyboardInterrupt,
        ]

        for function in functions:
            for error in errors:
                success_original = self._get_with_assertion_success(self.assertRaises, [error], function, [], True)
                success_test_case = self._get_with_assertion_success(self._testCase.assert_raises, [error], function, [], True)
                self.assertEqual(success_original, success_test_case)

        for function in functions:
            for regex in regexes:
                for error in errors:
                    if sys.version_info >= (3, 0):
                        success_original = self._get_with_assertion_success(self.assertRaisesRegex, [error, regex], function, [], True)
                    else:
                        success_original = self._get_with_assertion_success(self.assertRaisesRegexp, [error, regex], function, [], True)
                    success_test_case = self._get_with_assertion_success(self._testCase.assert_raises_regexp, [error, regex], function, [], True)
                    self.assertEqual(success_original, success_test_case)

                    # (self._testCase.assert_raises(self, exc, *args, **kwds)),
                    # (self._testCase.assert_raises_regexp(self, exc, r, *args, **kwds)),

    def _get_assertion_success(self, function, arguments, catch_exceptions=False):
        """
        Test if assertion-success
        :param function:    The function
        :param arguments:   The arguments
        :param catch_exceptions:        Catch exceptions
        :return:            Success
        """

        try:
            try:
                function(*arguments)
                return True
            except AssertionError:
                return False
        except Exception:
            if not catch_exceptions:
                raise
            return False

    def _get_with_assertion_success(self, function, arguments, sub_function, sub_function_arguments, catch_exceptions=False):
        """
        Test if assertion-success with 'with'
        :param function:                The function
        :param arguments:               The arguments
        :param sub_function:            The sub function
        :param sub_function_arguments:  The sub function arguments
        :param catch_exceptions:        Catch exceptions
        :return:                        Success
        """

        try:
            try:
                with function(*arguments):
                    sub_function(*sub_function_arguments)
                return True
            except AssertionError:
                return False
        except Exception:
            if not catch_exceptions:
                raise
            return False

    def test_skip(self):
        """
        Test skipping test
        :return:    void
        """

        self._testCase.skip('For testing purposes')
        raise RuntimeError('Should be skipped')

    def test_rand_str(self):
        """
        Test rand_str
        """

        # Test length
        self.assertEqual(0, len(self._testCase.rand_str(0)))
        self.assertEqual(7, len(self._testCase.rand_str(7)))
        self.assertEqual(23, len(self._testCase.rand_str(23)))
        self.assertNotEqual(23, len(self._testCase.rand_str(32)))

        # Test uniqueness
        self.assertEqual(self._testCase.rand_str(0), self._testCase.rand_str(0))
        self.assertNotEqual(self._testCase.rand_str(7), self._testCase.rand_str(7))
        self.assertNotEqual(self._testCase.rand_str(23), self._testCase.rand_str(23))
        self.assertNotEqual(self._testCase.rand_str(32), self._testCase.rand_str(32))

    def test_rand_int(self):
        """
        Test rand_int
        """

        self.assertLessEqual(1, self._testCase.rand_int(1, 10))
        self.assertGreaterEqual(10, self._testCase.rand_int(0, 10))
        self.assertLessEqual(1, self._testCase.rand_int(1, 100))
        self.assertGreaterEqual(100, self._testCase.rand_int(0, 100))
        self.assertLessEqual(1, self._testCase.rand_int(1, 1000))
        self.assertGreaterEqual(1000, self._testCase.rand_int(0, 1000))

    def test_temp(self):
        """
        Test temp directories and files
        :return:    void
        """

        dir = self._testCase.temp_dir()
        if not os.path.isdir(dir):
            os.makedirs(dir)
        if not os.path.isdir(dir):
            raise RuntimeError('Temp dir still does not exist')

        file = self._testCase.temp_file()
        if not os.path.isfile(file):
            open(file, 'a').close()

        self.assertTrue(os.path.isdir(dir))
        self.assertTrue(os.path.isfile(file))

        self._testCase.tearDown()

        self.assertFalse(os.path.isdir(dir))
        self.assertFalse(os.path.isfile(file))
