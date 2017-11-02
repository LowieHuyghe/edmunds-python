
# Testing

> Untested Code is Broken Code


## Creating tests

Add your own tests to the `tests`-directory and initiate them like so:
```python
from tests.testcase import TestCase

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

Nose is used for running the tests in the command line. Run them like so:
```bash
python manage.py test
python manage.py test --test-suite tests.testmycase
```


## Asserting

The `TestCase`-class extends the `unittest.TestCase`, but implements the default
assertion-functions in snake-case format:
```python
# a == b
self.assert_equal(a, b)
# == self.assertEqual(a, b)

# bool(x) is False
self.assert_false(x)
# == self.assertFalse(a, b)

# ...
```
