
# Testing

Unittesting is one of the basic keys to success when it comes down to application-uptime. Testing makes sure the application just works and keeps working after code-changes.

## Creating tests
Every edmunds-instance has a `main_test.py` in its root which can be run with python. This file states where the tests will be located.

Add your own tests to the `test`-directory and initiate them like so:
```python
from edmunds.foundation.testing.testcase import TestCase


class MyTest(TestCase):
    """
    Test my tests
    """

    def set_up(self):
        """
        Set up the test case
        """
        super(MyTest, self).set_up()

    def tear_down(self):
        """
        Tear down the test case
        """
        super(MyTest, self).tear_down()

    def test_something(self):
        """
        Test something
        """
        pass
```

## Running tests
Run your tests in command line with:
```bash
python main_test.py
```

## Asserting
The `TestCase`-class already implements a lot of assertion functions:
```python
# a == b
self.assert_equal(a, b)
# a != b
self.assert_not_equal(a, b)
# bool(x) is True
self.assert_true(x)
# bool(x) is False
self.assert_false(x)
# a is b
self.assert_is(a, b)
# a is not b
self.assert_is_not(a, b)
# x is None
self.assert_is_none(x)
# x is not None
self.assert_is_not_none(x)
# a in b
self.assert_in(a, b)
# a not in b
self.assert_not_in(a, b)
# isinstance(a, b)
self.assert_is_instance(a, b)
# not isinstance(a, b)
self.assert_not_is_instance(a, b)
# fun(*args, **kwds) raises exc
self.assert_raises(exc, fun, *args, **kwds)
# fun(*args, **kwds) raises exc and the message matches regex r
self.assert_raises_regexp(exc, r, fun, *args, **kwds)
# round(a-b, 7) == 0
self.assert_almost_equal(a, b)
# round(a-b, 7) != 0
self.assert_not_almost_equal(a, b)
# a > b
self.assert_greater(a, b)
# a >= b
self.assert_greater_equal(a, b)
# a < b
self.assert_less(a, b)
# a <= b
self.assert_less_equal(a, b)
# r.search(s)
self.assert_regexp_matches(s, r)
# not r.search(s)
self.assert_not_regexp_matches(s, r)
# sorted(a) == sorted(b) and works with unhashable objs
self.assert_items_equal(a, b)
# all the key/value pairs in a exist in b
self.assert_dict_contains_subset(a, b)
# strings
self.assert_multi_line_equal(a, b)
# sequences
self.assert_sequence_equal(a, b)
# lists
self.assert_list_equal(a, b)
# tuples
self.assert_tuple_equal(a, b)
# sets or frozensets
self.assert_set_equal(a, b)
# dicts
self.assert_dict_equal(a, b)
```