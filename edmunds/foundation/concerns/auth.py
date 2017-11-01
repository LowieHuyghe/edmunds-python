
class Auth(object):
    """
    This class concerns auth code for Application to extend from
    """

    def auth_security(self, name=None, no_instance_error=False):
        """
        The auth manager
        :param name:                The name of the auth instance
        :type  name:                str
        :param no_instance_error:   Error when no instance
        :type  no_instance_error:   bool
        :return:                    The auth driver
        :rtype:                     flask_security.Security
        """

        # Enabled?
        if not self.config('app.auth.enabled', False):
            return

        return self.extensions['edmunds.auth'].get(name, no_instance_error=no_instance_error)

    def auth_userdatastore(self, name=None, no_instance_error=False):
        """
        The auth manager
        :param name:                The name of the auth instance
        :type  name:                str
        :param no_instance_error:   Error when no instance
        :type  no_instance_error:   bool
        :return:                    The auth driver
        :rtype:                     flask_security.datastore.UserDatastore
        """

        security = self.auth_security(name=name, no_instance_error=no_instance_error)
        if security is None:
            return

        return security.datastore
