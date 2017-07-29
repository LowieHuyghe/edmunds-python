
from tests.testcase import TestCase
from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine
from geoip2.models import City as CityModel
from geoip2.records import Country, City, Location, Subdivisions, Subdivision


class TestGoogleAppEngine(TestCase):
    """
    Test the Google App Engine driver
    """

    def set_up(self):
        """
        Set up test
        :return:    void
        """
        super(TestGoogleAppEngine, self).set_up()

        self.write_config([
            "from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'location': { \n",
            "           'enabled': True, \n",
            "           'instances': [ \n",
            "               { \n",
            "                   'name': 'gae',\n",
            "                   'driver': GoogleAppEngine,\n",
            "               }, \n",
            "           ], \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ])
        self.app = self.create_application()

    def test_insights_outside_context(self):
        """
        Test insights outside context
        :return:    void
        """

        ip = '127.0.0.1'

        # Fetch driver
        driver = self.app.localization().location()
        self.assert_is_instance(driver, GoogleAppEngine)

        # Insights outside context
        with self.assert_raises_regexp(RuntimeError, 'Working outside of request context'):
            driver.insights(ip)

    def test_insights_different_ip(self):
        """
        Test insights outside context
        :return:    void
        """

        ip = '127.0.0.1'
        other_ip = '127.0.0.2'
        rule = '/' + self.rand_str(20)

        # Fetch driver
        driver = self.app.localization().location()
        self.assert_is_instance(driver, GoogleAppEngine)

        # Within context no headers
        with self.app.test_request_context(rule, environ_base={'REMOTE_ADDR': ip}):
            with self.assert_raises_regexp(RuntimeError, 'Can only use GoogleAppEngine-location-driver for looking up location of request-ip \(=%s\) \(lookup=%s\)' % (ip, other_ip)):
                driver.insights(other_ip)

    def test_insights_no_headers(self):
        """
        Test insights no headers
        :return:    void
        """

        ip = '127.0.0.1'
        rule = '/' + self.rand_str(20)
        environ_base={'REMOTE_ADDR': ip}

        # Fetch driver
        driver = self.app.localization().location()
        self.assert_is_instance(driver, GoogleAppEngine)

        # Within context no headers
        with self.app.test_request_context(rule, environ_base=environ_base):
            city = driver.insights(ip)
            self.assert_is_instance(city, CityModel)

            self.assert_is_instance(city.country, Country)
            self.assert_is_none(city.country.iso_code)

            self.assert_is_instance(city.subdivisions, Subdivisions)
            self.assert_is_instance(city.subdivisions.most_specific, Subdivision)
            self.assert_is_none(city.subdivisions.most_specific.iso_code)

            self.assert_is_instance(city.city, City)
            self.assert_is_none(city.city.name)

            self.assert_is_instance(city.location, Location)
            self.assert_is_none(city.location.latitude)
            self.assert_is_none(city.location.longitude)
            self.assert_is_none(city.location.time_zone)

    def test_insights_headers(self):
        """
        Test insights headers
        :return:    void
        """

        ip = '127.0.0.1'
        rule = '/' + self.rand_str(20)
        environ_base={'REMOTE_ADDR': ip}
        country_iso = 'BE'
        region_iso = 'WV'
        city_name = 'Berlin'
        latitude = 52.5061
        longitude = 13.358
        timezone = 'Europe/Berlin'
        headers = {
            'X-AppEngine-Country': country_iso,
            'X-AppEngine-Region': region_iso,
            'X-AppEngine-City': city_name,
            'X-AppEngine-CityLatLong': '%s,%s' % (latitude, longitude),
        }

        # Fetch driver
        driver = self.app.localization().location()
        self.assert_is_instance(driver, GoogleAppEngine)

        # Within context no headers
        with self.app.test_request_context(rule, environ_base=environ_base, headers=headers):
            city = driver.insights(ip)
            self.assert_is_instance(city, CityModel)

            self.assert_is_instance(city.country, Country)
            self.assert_equal(country_iso, city.country.iso_code)

            self.assert_is_instance(city.subdivisions, Subdivisions)
            self.assert_is_instance(city.subdivisions.most_specific, Subdivision)
            self.assert_equal(region_iso, city.subdivisions.most_specific.iso_code)

            self.assert_is_instance(city.city, City)
            self.assert_equal(city_name, city.city.name)

            self.assert_is_instance(city.location, Location)
            self.assert_equal(latitude, city.location.latitude)
            self.assert_equal(longitude, city.location.longitude)
            self.assert_equal(timezone, city.location.time_zone)
