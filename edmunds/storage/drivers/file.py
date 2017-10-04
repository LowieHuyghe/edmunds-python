
from edmunds.storage.drivers.basedriver import BaseDriver
import os
import shutil


class File(BaseDriver):
    """
    File driver
    """

    def __init__(self, app, storage_path, files_path, prefix=''):
        """
        Initiate the instance
        :param app:             The application
        :type  app:             Application
        :param storage_path:    The storage path
        :type  storage_path:    str
        :param files_path:      The files path
        :type  files_path:      str
        :param prefix:          The prefix for storing
        :type  prefix:          str
        """

        super(File, self).__init__(app)

        self._storage_path = storage_path
        self._files_path = files_path
        self._prefix = prefix

    def write_stream(self, path, append=False, prefix=None):
        """
        Get a write stream to a certain path
        :param path:    The path to the file
        :type  path:    str
        :param append:  Append to the file
        :type  append:  bool
        :param prefix:  The prefix
        :type  prefix:  str
        :return:        The write stream
        :rtype:         Stream
        """

        path = self.path(path, prefix=prefix)

        return open(path, 'a' if append else 'w+')

    def read_stream(self, path, raise_errors=False, prefix=None):
        """
        Get a read stream to a certain path
        :param path:            The path to the file
        :type  path:            str
        :param raise_errors:    Raise the errors
        :type  raise_errors:    bool
        :param prefix:          The prefix
        :type  prefix:          str
        :return:                The read stream
        :rtype:                 Stream
        """

        path = self.path(path, prefix=prefix)

        try:
            return open(path, 'r')

        except (IOError, OSError):
            if raise_errors:
                raise
            else:
                return None

    def copy(self, path, new_path, raise_errors=False, prefix=None):
        """
        Copy a certain path
        :param path:            The path to the file
        :type  path:            str
        :param new_path:        The path to the new file
        :type  new_path:        str
        :param raise_errors:    Raise the errors
        :type  raise_errors:    bool
        :param prefix:          The prefix
        :type  prefix:          str
        :return:                Success
        :rtype:                 bool
        """

        path = self.path(path, prefix=prefix)
        new_path = self.path(new_path)

        try:
            shutil.copy2(path, new_path)
            return True

        except (IOError, OSError):
            if raise_errors:
                raise
            else:
                return False

    def delete(self, path, raise_errors=False, prefix=None):
        """
        Delete a certain path
        :param path:            The path to the file
        :type  path:            str
        :param raise_errors:    Raise the errors
        :type  raise_errors:    bool
        :param prefix:          The prefix
        :type  prefix:          str
        :return:                Success
        :rtype:                 bool
        """

        path = self.path(path, prefix=prefix)

        try:
            os.remove(path)
            return True

        except (IOError, OSError):
            if raise_errors:
                raise
            else:
                return False

    def exists(self, path, prefix=None):
        """
        Check if a certain path exists
        :param path:    The path to the file
        :type  path:    str
        :param prefix:  The prefix
        :type  prefix:  str
        :return:        Exists
        :rtype:         bool
        """

        path = self.path(path, prefix=prefix)

        return os.path.isfile(path)

    def path(self, path, prefix=None):
        """
        Get the processed path
        :param path:    The path to the file
        :type  path:    str
        :param prefix:  The prefix
        :type  prefix:  str
        :return:        The processed path to the file
        :rtype:         str
        """

        if prefix is None:
            prefix = self._prefix

        if path is not None and prefix != '' and not path.endswith(os.sep):
            path_parts = path.split(os.sep)

            filename = path_parts.pop()
            if not filename.startswith(prefix):
                filename = prefix + filename
            path_parts.append(filename)

            path = os.sep.join(path_parts)

        if path is None:
            path = os.path.join(self._storage_path, self._files_path) + os.sep
        elif not path.startswith(os.sep):
            path = os.path.join(self._storage_path, self._files_path, path)
        else:
            path = os.path.join(self._storage_path, path[1:])

        return path
