
from tests.testcase import TestCase
from edmunds.profiler.drivers.basedriver import BaseDriver


class TestBaseDriver(TestCase):
    """
    Test the Base Driver
    """

    def test_no_abstract(self):
        """
        Test no abstract methods
        :return:    void
        """

        with self.assert_raises_regexp(TypeError, 'process'):
            MyBaseDriverNoAbstract(self.app)

    def test_with_abstract(self):
        """
        Test required abstract methods
        """

        driver = MyBaseDriver(self.app)
        self.assert_is_instance(driver, MyBaseDriver)

        # Call each method once (for test coverage as the 'pass' in the parent is not run)
        driver.process(self.rand_str(), self.rand_int(1, 10), self.rand_int(11, 20), self.rand_str(), self.rand_str())


class MyBaseDriverNoAbstract(BaseDriver):
    pass


class MyBaseDriver(BaseDriver):

    def process(self, profiler, start, end, environment, suggestive_file_name):
        super(MyBaseDriver, self).process(profiler, start, end, environment, suggestive_file_name)
