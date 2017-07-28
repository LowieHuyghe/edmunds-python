
from geoip2.database import Reader
from edmunds.localization.location.drivers.basedriver import BaseDriver


class MaxMindCityDatabase(BaseDriver):
    """
    MaxMind City Database
    """

    def __init__(self, database):
        """
        Constructor
        :param database:    Location of the city-database
        """
        self._reader = Reader(database)

    def insights(self, ip):
        """
        Get insights in ip
        :param ip:  The ip
        :return:    Insights
        :rtype:     geoip2.models.City
        """
        return self._reader.city(ip)

    def close(self):
        """
        Close the reader
        :return:    void
        """
        self._reader.close()
