
import os
import re


class RuntimeEnvironment(object):
    """
    The Google App Engine runtime environment
    """

    @staticmethod
    def is_gae():
        """
        Check if is Google App Engine
        """

        if 'CURRENT_VERSION_ID' not in os.environ:
            return False

        if 'AUTH_DOMAIN' not in os.environ:
            return False

        if 'SERVER_SOFTWARE' not in os.environ:
            return False

        server_software = os.environ.get('SERVER_SOFTWARE')

        if not re.match(r'^Development\/', server_software) and not re.match(r'^Google App Engine\/', server_software):
            return False

        return True

    @staticmethod
    def is_gae_development():
        """
        Check if Google App Engine SDK
        :return:    Gae Development
        :rtype:     bool
        """
        if not RuntimeEnvironment.is_gae():
            return False

        return 'SERVER_SOFTWARE' in os.environ \
               and os.environ['SERVER_SOFTWARE'].startswith('Development')
