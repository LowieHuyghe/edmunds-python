
from edmunds.storage.storagemanager import StorageManager
import os


class Storage(object):
    """
    This class concerns storage code for Application to extend from
    """

    def _init_storage(self):
        """
        Initialise concerning storage
        """

        self._storage_manager = StorageManager(self, self.root_path, 'storage')


    def fs(self, name = None):
        """
        The filesystem to use
        :param name:    The name of the storage instance
        :type  name:    str
        :return:        The file system
        :rtype:         Edmunds.Storage.Drivers.BaseDriver
        """

        return self._storage_manager.get(name)
