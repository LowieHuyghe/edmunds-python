
from tests.gae.gaetestcase import GaeTestCase
import os
if GaeTestCase.can_run():
    import cloudstorage as gcs


class TestGoogleCloudStorage(GaeTestCase):
    """
    Test the GoogleCloudStorage
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestGoogleCloudStorage, self).set_up()

        self.testbed.init_app_identity_stub()
        self.testbed.init_memcache_stub()
        self.testbed.init_urlfetch_stub()
        self.testbed.init_blobstore_stub()
        self.testbed.init_datastore_v3_stub()

        self.prefix = self.rand_str(20) + '.'
        self.storage_directory = os.sep + 'storage' + os.sep
        self.clear_paths = []

    def test_google_cloud_storage(self):
        """
        Test the google cloud storage
        """

        string = self.rand_str(20)

        # Write config
        self.write_config([
            "from edmunds.storage.drivers.googlecloudstorage import GoogleCloudStorage \n",
            "from logging import WARNING \n",
            "APP = { \n",
            "   'storage': { \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'googlecloudstorage',\n",
            "               'driver': GoogleCloudStorage,\n",
            "               'directory': '%s',\n" % self.storage_directory,
            "               'prefix': '%s',\n" % self.prefix,
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "   'log': { \n",
            "       'instances': [ \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()
        directory = app.fs()._get_processed_path(self.storage_directory)
        self.clear_paths.append(directory)
        self.assert_equal(self.storage_directory, app.config('app.storage.instances')[0]['directory'])
        self.assert_equal(self.prefix, app.config('app.storage.instances')[0]['prefix'])

        # Write
        stream = app.fs().write_stream('nice.txt')
        try:
            stream.write(string)
        finally:
            stream.close()

        # Read
        stream = app.fs().read_stream('nice.txt')
        try:
            self.assert_in(string, stream.read())
        finally:
            stream.close()

        # Copy
        self.assert_true(app.fs().copy('nice.txt', 'nice2.txt'))
        stream = app.fs().read_stream('nice2.txt')
        try:
            self.assert_in(string, stream.read())
        finally:
            stream.close()

        # Exists and delete
        self.assert_true(app.fs().exists('nice2.txt'))
        self.assert_true(app.fs().delete('nice2.txt'))
        self.assert_false(app.fs().exists('nice2.txt'))

        # Read stream non existing
        self.assert_false(app.fs().read_stream('nice2.txt'))
        with self.assert_raises(gcs.NotFoundError):
            app.fs().read_stream('nice2.txt', raise_errors=True)

        # Copy non existing
        self.assert_false(app.fs().copy('nice2.txt', 'nice.txt'))
        with self.assert_raises(gcs.NotFoundError):
            app.fs().copy('nice2.txt', 'nice.txt', raise_errors=True)

        # Delete non existing
        self.assert_false(app.fs().delete('nice2.txt'))
        with self.assert_raises(gcs.NotFoundError):
            app.fs().delete('nice2.txt', raise_errors=True)
