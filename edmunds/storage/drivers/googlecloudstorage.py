
from edmunds.storage.drivers.basedriver import BaseDriver
import os
import cloudstorage as gcs


class GoogleCloudStorage(BaseDriver):
    """
    GoogleCloudStorage driver
    """

    def __init__(self, app, bucket, storage_path, files_path, prefix=''):
        """
        Initiate the instance
        :param app:             The application
        :type  app:             Application
        :param bucket:          The bucket
        :type  bucket:          str
        :param storage_path:    The storage path
        :type  storage_path:    str
        :param files_path:      The files path
        :type  files_path:      str
        :param prefix:          The prefix for storing
        :type  prefix:          str
        """

        super(GoogleCloudStorage, self).__init__(app)

        self._bucket = bucket
        self._storage_path = storage_path
        self._files_path = files_path
        self._prefix = prefix

    def write_stream(self, path):
        """
        Get a write stream to a certain path
        :param path:    The path to the file
        :type  path:    str
        :return:        The write stream
        :rtype:         Stream
        """

        path = self.path(path)

        return gcs.open(path, 'w')

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

        path = self.path(path)

        try:
            return gcs.open(path, 'r')

        except (gcs.NotFoundError, gcs.AuthorizationError) as e:
            if raise_errors:
                raise e
            else:
                return None

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

        path = self.path(path)
        new_path = self.path(new_path)

        try:
            gcs.copy2(path, new_path)
            return True

        except (gcs.NotFoundError, gcs.AuthorizationError) as e:
            if raise_errors:
                raise e
            else:
                return False

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

        path = self.path(path)

        try:
            gcs.delete(path)
            return True

        except gcs.NotFoundError as e:
            if raise_errors:
                raise e
            else:
                return False

    def exists(self, path):
        """
        Check if a certain path exists
        :param path:    The path to the file
        :type  path:    str
        :return:        Exists
        :rtype:         bool
        """

        path = self.path(path)

        try:
            gcs.stat(path)
            return True

        except gcs.NotFoundError as e:
            return False

    def path(self, path):
        """
        Get the processed path
        :param path:    The path to the file
        :type  path:    str
        :return:        The processed path to the file
        :rtype:         str
        """

        if path is not None and self._prefix != '' and not path.endswith(os.sep):
            path_parts = path.split(os.sep)

            filename = path_parts.pop()
            if not filename.startswith(self._prefix):
                filename = self._prefix + filename
            path_parts.append(filename)

            path = os.sep.join(path_parts)

        if path is None:
            path = os.path.join(self._storage_path, self._files_path)
        elif not path.startswith(os.sep):
            path = os.path.join(self._storage_path, self._files_path, path)
        else:
            path = os.path.join(self._storage_path, path[1:])

        path = os.path.join(os.sep, self._bucket, path)

        return path
