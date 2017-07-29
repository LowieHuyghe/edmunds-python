
from edmunds.foundation.patterns.manager import Manager
from edmunds.localization.location.drivers.maxmindcitydatabase import MaxMindCityDatabase
from edmunds.localization.location.drivers.maxmindenterprisedatabase import MaxMindEnterpriseDatabase
from edmunds.localization.location.drivers.maxmindwebservice import MaxMindWebService
from edmunds.localization.location.drivers.googleappengine import GoogleAppEngine


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

        super(LocationManager, self).__init__(app, app.config('app.localization.location.instances', []))

    def _create_max_mind_city_database(self, config):
        """
        Create MaxMind City Database driver
        :param config:  The config
        :type  config:  dict
        :return:        Driver
        :rtype:         edmunds.localization.location.drivers.maxmindcitydatabase.MaxMindCityDatabase
        """

        if 'database' not in config:
            raise RuntimeError("MaxMindCityDatabase-driver '%s' is missing some configuration ('database' is required)." % config['name'])

        database = config['database']
        return MaxMindCityDatabase(database)

    def _create_max_mind_enterprise_database(self, config):
        """
        Create MaxMind Enterprise Database driver
        :param config:  The config
        :type  config:  dict
        :return:        Driver
        :rtype:         edmunds.localization.location.drivers.maxmindenterprisedatabase.MaxMindEnterpriseDatabase
        """

        if 'database' not in config:
            raise RuntimeError("MaxMindEnterpriseDatabase-driver '%s' is missing some configuration ('database' is required)." % config['name'])

        database = config['database']
        return MaxMindEnterpriseDatabase(database)

    def _create_max_mind_web_service(self, config):
        """
        Create MaxMind Enterprise Database driver
        :param config:  The config
        :type  config:  dict
        :return:        Driver
        :rtype:         edmunds.localization.location.drivers.maxmindwebservice.MaxMindWebService
        """

        if 'user_id' not in config \
                or 'license_key' not in config:
            raise RuntimeError("MaxMindWebService-driver '%s' is missing some configuration ('user_id' and 'license_key' are required)." % config['name'])

        user_id = config['user_id']
        license_key = config['license_key']
        return MaxMindWebService(user_id, license_key)

    def _create_google_app_engine(self, config):
        """
        Create Google App Engine driver
        :param config:  The config
        :type  config:  dict
        :return:        Driver
        :rtype:         edmunds.localization.location.drivers.googleappengine.GoogleAppEngine
        """

        return GoogleAppEngine()
