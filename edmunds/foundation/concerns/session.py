
class Session(object):
    """
    This class concerns session code for Application to extend from
    """

    def session(self, name=None, no_instance_error=False):
        """
        The session to use
        :param name:                The name of the session instance
        :type  name:                str
        :param no_instance_error:   Error when no instance
        :type  no_instance_error:   bool
        :return:                    The session driver
        """

        # Enabled?
        if not self.config('app.session.enabled', False):
            return None

        return self.extensions['edmunds.session'].get(name, no_instance_error=no_instance_error)
