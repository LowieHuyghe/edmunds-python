
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
