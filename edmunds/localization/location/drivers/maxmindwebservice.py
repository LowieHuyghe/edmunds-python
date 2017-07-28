
from geoip2.webservice import Client
from edmunds.localization.location.drivers.basedriver import BaseDriver


class MaxMindWebService(BaseDriver):
    """
    MaxMind Web Service
    """

    def __init__(self, user_id, license_key):
        """
        Constructor
        :param user_id:     MaxMind user-id 
        :param license_key: MaxMind license key
        """
        self._client = Client(user_id, license_key)

    def insights(self, ip):
        """
        Get insights in ip
        :param ip:  The ip
        :return:    Insights
        :rtype:     geoip2.models.City
        """
        return self._client.insights(ip)
