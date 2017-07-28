
from geoip2.database import Reader
from edmunds.localization.location.drivers.basedriver import BaseDriver
import atexit


class MaxMindEnterpriseDatabase(BaseDriver):
    """
    MaxMind Enterprise Database
    """

    def __init__(self, database):
        """
        Constructor
        :param database:    Location of the enterprise-database
        """
        self._reader = Reader(database)

        # Close reader when app closes down
        atexit.register(lambda: self._reader.close())

    def insights(self, ip):
        """
        Get insights in ip
        :param ip:  The ip
        :return:    Insights
        :rtype:     geoip2.models.City
        """
        return self._reader.enterprise(ip)
