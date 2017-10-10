
from edmunds.localization.location.drivers.basedriver import BaseDriver
from edmunds.globals import request
from geoip2.models import City
from timezonefinderL import TimezoneFinder
from edmunds.encoding.encoding import Encoding


class GoogleAppEngine(BaseDriver):
    """
    Google App Engine driver
    """

    def insights(self, ip):
        """
        Get insights in ip
        :param ip:  The ip
        :return:    Insights
        :rtype:     geoip2.models.City
        """
        if request.remote_addr != ip:
            raise RuntimeError("Can only use GoogleAppEngine-location-driver for looking up location of request-ip (=%s) (lookup=%s)" % (request.remote_addr, ip))

        raw_response = {}

        # Country
        country_iso = request.headers['X-AppEngine-Country'] if 'X-AppEngine-Country' in request.headers else None
        country_iso = country_iso if country_iso and country_iso != 'ZZ' else None
        if country_iso:
            country_iso = Encoding.normalize(country_iso)
            raw_response['country'] = {
                'iso_code': country_iso
            }
            raw_response['registered_country'] = raw_response['country']
            raw_response['represented_country'] = raw_response['country']

        # Region
        region_iso = request.headers['X-AppEngine-Region'] if 'X-AppEngine-Region' in request.headers else None
        region_iso = region_iso if region_iso else None
        if region_iso:
            region_iso = Encoding.normalize(region_iso)
            raw_response['subdivisions'] = [
                {
                    'iso_code': region_iso
                }
            ]

        # City
        city = request.headers['X-AppEngine-City'] if 'X-AppEngine-City' in request.headers else None
        city = city if city else None
        if city:
            city = Encoding.normalize(city)
            raw_response['city'] = {
                'names': {
                    'en': city
                }
            }

        # Location
        city_lat_long = request.headers['X-AppEngine-CityLatLong'] if 'X-AppEngine-CityLatLong' in request.headers else None
        city_lat_long = city_lat_long if city_lat_long else None
        latitude, longitude = city_lat_long.split(',') if city_lat_long else (None, None)
        if latitude and longitude:
            latitude = float(Encoding.normalize(latitude))
            longitude = float(Encoding.normalize(longitude))

            raw_response['location'] = {
                'latitude': latitude,
                'longitude': longitude,
            }

            timezone_finder = TimezoneFinder()
            timezone = timezone_finder.timezone_at(lat=latitude, lng=longitude)
            timezone = timezone if timezone else None
            if timezone:
                raw_response['location']['time_zone'] = timezone

        return City(raw_response)
