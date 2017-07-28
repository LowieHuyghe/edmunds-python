
from edmunds.foundation.patterns.manager import Manager
from edmunds.localization.location.drivers.maxmindcitydatabase import MaxMindCityDatabase
from edmunds.localization.location.drivers.maxmindenterprisedatabase import MaxMindEnterpriseDatabase
from edmunds.localization.location.drivers.maxmindwebservice import MaxMindWebService


class LocationManager(Manager):
    """
    Location Manager
    """

    def __init__(self, app):
        """
        Initiate the manager
        :param app:     The application
        :type  app:     Application
        """

        super(LocationManager, self).__init__(app, app.config('app.location.instances', []))

    def create_max_mind_city_database_driver(self, config):
        """
        Create MaxMind City Database driver
        :param config:  The config
        :type  config:  dict
        :return:    Driver
        :rtype:     edmunds.localization.location.drivers.maxmindcitydatabase.MaxMindCityDatabase
        """

        if 'database' not in config:
            raise RuntimeError("MaxMindCityDatabase-driver '%s' is missing some configuration ('database' is required)." % config['name'])

        database = config['database']
        return MaxMindCityDatabase(database)

    def create_max_mind_enterprise_database_driver(self, config):
        """
        Create MaxMind Enterprise Database driver
        :param config:  The config
        :type  config:  dict
        :return:    Driver
        :rtype:     edmunds.localization.location.drivers.maxmindenterprisedatabase.MaxMindEnterpriseDatabase
        """

        if 'database' not in config:
            raise RuntimeError("MaxMindEnterpriseDatabase-driver '%s' is missing some configuration ('database' is required)." % config['name'])

        database = config['database']
        return MaxMindEnterpriseDatabase(database)

    def create_max_mind_web_service_driver(self, config):
        """
        Create MaxMind Enterprise Database driver
        :param config:  The config
        :type  config:  dict
        :return:    Driver
        :rtype:     edmunds.localization.location.drivers.maxmindwebservice.MaxMindWebService
        """

        if 'user_id' not in config \
                or 'license_key' not in config:
            raise RuntimeError("MaxMindWebService-driver '%s' is missing some configuration ('user_id' and 'license_key' are required)." % config['name'])

        user_id = config['user_id']
        license_key = config['license_key']
        return MaxMindWebService(user_id, license_key)