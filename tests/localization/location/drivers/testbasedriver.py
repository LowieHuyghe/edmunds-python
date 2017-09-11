
from tests.testcase import TestCase
from edmunds.localization.location.drivers.basedriver import BaseDriver


class TestBaseDriver(TestCase):
    """
    Test the Base Driver
    """

    def test_no_abstract_insights(self):
        """
        Test if abstract insights method is required
        """

        with self.assert_raises_regexp(TypeError, 'insights'):
            MyBaseDriverNoAbstractInsights()

    def test_abstract_insights(self):
        """
        Test required abstract insights method
        """

        provider = MyBaseDriverAbstractInsights()
        self.assert_is_instance(provider, MyBaseDriverAbstractInsights)

        # Call each method once (for test coverage as the 'pass' in the parent is not run)
        provider.insights('127.0.0.1')


class MyBaseDriverNoAbstractInsights(BaseDriver):
    """
    Base Driver class with missing insights method
    """

    pass


class MyBaseDriverAbstractInsights(BaseDriver):
    """
    Base Driver class with insights method
    """

    def insights(self, ip):
        super(MyBaseDriverAbstractInsights, self).insights(ip)
