
class Database(object):
    """
    This class concerns database code for Application to extend from
    """

    def database(self, name=None, no_instance_error=False):
        """
        The database to use
        :param name:                The name of the database instance
        :type  name:                str
        :param no_instance_error:   Error when no instance
        :type  no_instance_error:   bool
        :return:                    The database driver
        :rtype:                     sqlalchemy.engine.base.Engine
        """

        # Enabled?
        if not self.config('app.database.enabled', False):
            return None

        return self.extensions['edmunds.database'].get(name, no_instance_error=no_instance_error)
