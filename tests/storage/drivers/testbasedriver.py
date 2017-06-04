
from tests.testcase import TestCase
from edmunds.storage.drivers.basedriver import BaseDriver


class TestBaseDriver(TestCase):
    """
    Test the Base Driver
    """

    def test_no_abstract(self):
        """
        Test no abstract methods
        :return:    void
        """

        with self.assert_raises_regexp(TypeError, 'copy.*?delete.*?exists.*?read_stream.*?write_stream'):
            MyBaseDriverNoAbstract(self.app)

    def test_with_abstract(self):
        """
        Test required abstract methods
        """

        driver = MyBaseDriver(self.app)
        self.assert_is_instance(driver, MyBaseDriver)

        # Call each method once (for test coverage as the 'pass' in the parent is not run)
        driver.write_stream(self.rand_str())
        driver.read_stream(self.rand_str())
        driver.copy(self.rand_str(), self.rand_str())
        driver.delete(self.rand_str())
        driver.exists(self.rand_str())


class MyBaseDriverNoAbstract(BaseDriver):
    pass


class MyBaseDriver(BaseDriver):

    def write_stream(self, path):
        super(MyBaseDriver, self).write_stream(path)

    def read_stream(self, path):
        super(MyBaseDriver, self).read_stream(path)

    def copy(self, path, new_path):
        super(MyBaseDriver, self).copy(path, new_path)

    def delete(self, path):
        super(MyBaseDriver, self).delete(path)

    def exists(self, path):
        super(MyBaseDriver, self).exists(path)

    def path(self, path):
        super(MyBaseDriver, self).path(path)
