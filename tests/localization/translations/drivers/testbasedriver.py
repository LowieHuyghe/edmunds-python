
from tests.testcase import TestCase
from edmunds.localization.translations.drivers.basedriver import BaseDriver
from edmunds.localization.translations.sentencefiller import SentenceFiller


class TestBaseDriver(TestCase):
    """
    Test the Base Driver
    """

    def test_no_abstract_get(self):
        """
        Test if abstract get method is required
        """

        with self.assert_raises_regexp(TypeError, 'get'):
            MyBaseDriverNoAbstractGet(self.app, SentenceFiller())

    def test_abstract_get(self):
        """
        Test required abstract get method
        """

        provider = MyBaseDriverAbstractGet(self.app, SentenceFiller())
        self.assert_is_instance(provider, MyBaseDriverAbstractGet)

        # Call each method once (for test coverage as the 'pass' in the parent is not run)
        provider.get(None, None)


class MyBaseDriverNoAbstractGet(BaseDriver):
    """
    Base Driver class with missing get method
    """

    pass


class MyBaseDriverAbstractGet(BaseDriver):
    """
    Base Driver class with get method
    """

    def get(self, localization, key, parameters=None):
        super(MyBaseDriverAbstractGet, self).get(localization, key, parameters=parameters)
