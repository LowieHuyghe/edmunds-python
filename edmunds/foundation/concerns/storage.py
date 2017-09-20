
class Storage(object):
    """
    This class concerns storage code for Application to extend from
    """

    def fs(self, name=None):
        """
        The filesystem to use
        :param name:    The name of the storage instance
        :type  name:    str
        :return:        The file system
        :rtype:         edmunds.storage.drivers.basedriver.BaseDriver
        """

        return self.extensions['edmunds.storage'].get(name)
