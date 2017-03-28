
from tests.testcase import TestCase
from edmunds.support.serviceprovider import ServiceProvider


class TestServiceProviders(TestCase):
    """
    Test the Service Providers
    """

    cache = None

    def set_up(self):
        """
        Set up the test case
        """

        super(TestServiceProviders, self).set_up()

        TestServiceProviders.cache = dict()
        TestServiceProviders.cache['timeline'] = []

    def test_register(self):
        """
        Test register function
        :return:    void
        """

        # Register
        self.app.register(MyServiceProvider)

        self.assert_in(MyServiceProvider, self.app._registered_service_providers)

        self.assert_equal(1, len(TestServiceProviders.cache['timeline']))
        self.assert_equal(MyServiceProvider.__name__ + '.registered', TestServiceProviders.cache['timeline'][0])

    def test_double_register(self):
        """
        Test double register
        :return:    void
        """

        # Register
        self.app.register(MyServiceProvider)
        self.app.register(MyServiceProvider)

        self.assert_in(MyServiceProvider, self.app._registered_service_providers)

        self.assert_equal(1, len(TestServiceProviders.cache['timeline']))
        self.assert_equal(MyServiceProvider.__name__ + '.registered', TestServiceProviders.cache['timeline'][0])


class MyServiceProvider(ServiceProvider):

    def register(self):
        """
        Register the service provider
        """

        TestServiceProviders.cache['timeline'].append(self.__class__.__name__ + '.registered')
