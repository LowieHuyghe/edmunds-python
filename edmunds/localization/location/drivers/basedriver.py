
import abc
ABC = abc.ABCMeta('ABC', (object,), {})


class BaseDriver(ABC):
    """
    The base driver for location-drivers
    """

    @abc.abstractmethod
    def insights(self, ip):
        """
        Get insights in ip
        :param ip:  The ip
        :return:    Insights
        :rtype:     geoip2.models.City
        """
        pass
