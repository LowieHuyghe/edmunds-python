
class Cache(object):
    """
    This class concerns cache code for Application to extend from
    """

    def cache(self, name=None, no_instance_error=False):
        """
        The cache manager
        :param name:                The name of the session instance
        :type  name:                str
        :param no_instance_error:   Error when no instance
        :type  no_instance_error:   bool
        :return:                    The cache driver
        :rtype:                     werkzeug.contrib.cache.BaseCache
        """

        # Enabled?
        if not self.config('app.cache.enabled', False):
            return

        return self.extensions['edmunds.cache'].get(name, no_instance_error=no_instance_error)
