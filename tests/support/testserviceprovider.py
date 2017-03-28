
from tests.testcase import TestCase
from edmunds.support.serviceprovider import ServiceProvider


class TestServiceProvider(TestCase):
    """
    Test the Service Provider
    """

    def test_no_abstract_register(self):
        """
        Test if abstract register method is required
        """

        with self.assert_raises_regexp(TypeError, 'register'):
            MyServiceProviderNoAbstractRegister(self.app)

    def test_abstract_register(self):
        """
        Test required abstract register method
        """

        provider = MyServiceProviderAbstractRegister(self.app)
        self.assert_is_instance(provider, MyServiceProviderAbstractRegister)

        # Call each method once (for test coverage as the 'pass' in the parent is not run)
        provider.register()


class MyServiceProviderNoAbstractRegister(ServiceProvider):
    """
    Service Provider class with missing register method
    """

    pass


class MyServiceProviderAbstractRegister(ServiceProvider):
    """
    Service Provider class with register method
    """

    def register(self):
        super(MyServiceProviderAbstractRegister, self).register()
