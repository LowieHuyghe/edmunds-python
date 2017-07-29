
from tests.testcase import TestCase
from edmunds.localization.location.drivers.maxmindwebservice import MaxMindWebService
from geoip2.errors import AuthenticationError


class TestMaxMindWebService(TestCase):
    """
    Test the MaxMind Web Service driver
    """

    def set_up(self):
        """
        Set up
        :return:    void 
        """
        super(TestMaxMindWebService, self).set_up()

        self.config = [
            "from edmunds.localization.location.drivers.maxmindwebservice import MaxMindWebService \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'location': { \n",
            "           'enabled': True, \n",
            "           'instances': [ \n",
            "               { \n",
            "                   'name': 'maxmindweb',\n",
            "                   'driver': MaxMindWebService,\n",
            "                   'user_id': 1,\n",
            "                   'license_key': 'license_key',\n",
            "               }, \n",
            "           ], \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ]

    def test_missing_params(self):
        """
        Test missing params
        :return:    void
        """

        remove_lines = [9, 10]

        # Loop lines that should be individually removed
        for remove_line in remove_lines:
            new_config = self.config[:]
            del new_config[remove_line]

            self.write_config(new_config)

            # Create app
            app = self.create_application()

            # Error on loading of config
            with self.assert_raises_regexp(RuntimeError, 'missing some configuration'):
                app.localization().location()

    def test_insights(self):
        """
        Test insights
        :return:    void
        """

        ip = '127.0.0.1'

        # Write config and create application
        self.write_config(self.config)
        app = self.create_application()

        # Fetch driver
        driver = app.localization().location()
        self.assert_is_instance(driver, MaxMindWebService)

        # Check insights
        with self.assert_raises_regexp(AuthenticationError, 'Your user ID or license key could not be authenticated.'):
            driver.insights(ip)
