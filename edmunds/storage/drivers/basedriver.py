
import abc
ABC = abc.ABCMeta('ABC', (object,), {})


class BaseDriver(ABC):
    """
    The base driver for storage-drivers
    """

    def __init__(self, app):
        """
        Initiate the instance
        :param app:                         The application
        :type  app:                         Edmunds.Application
        """

        self._app = app

    @abc.abstractmethod
    def write_stream(self, path):
        """
        Get a write stream to a certain path
        :param path:    The path to the file
        :type  path:    str
        :return:        The write stream
        :rtype:         Stream
        """
        pass

    @abc.abstractmethod
    def read_stream(self, path, raise_errors=False):
        """
        Get a read stream to a certain path
        :param path:            The path to the file
        :type  path:            str
        :param raise_errors:    Raise the errors
        :type  raise_errors:    bool
        :return:                The read stream
        :rtype:                 Stream
        """
        pass

    @abc.abstractmethod
    def copy(self, path, new_path, raise_errors=False):
        """
        Copy a certain path
        :param path:            The path to the file
        :type  path:            str
        :param new_path:        The path to the new file
        :type  new_path:        str
        :param raise_errors:    Raise the errors
        :type  raise_errors:    bool
        :return:                Success
        :rtype:                 bool
        """
        pass

    @abc.abstractmethod
    def delete(self, path, raise_errors=False):
        """
        Delete a certain path
        :param path:            The path to the file
        :type  path:            str
        :param raise_errors:    Raise the errors
        :type  raise_errors:    bool
        :return:                Success
        :rtype:                 bool
        """
        pass

    @abc.abstractmethod
    def exists(self, path):
        """
        Check if a certain path exists
        :param path:    The path to the file
        :type  path:    str
        :return:        Exists
        :rtype:         bool
        """
        pass
