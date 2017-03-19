
from tests.testcase import TestCase
import os


class TestGoogleCloudStorage(TestCase):
    """
    Test the GoogleCloudStorage
    """

    def set_up(self):
        """w
        Set up the test case
        """

        super(TestGoogleCloudStorage, self).set_up()

        self.prefix = self.rand_str(20) + '.'
        self.storage_directory = os.sep + 'storage' + os.sep
        self.clear_paths = []

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestGoogleCloudStorage, self).tear_down()

        # TODO: Delete the files

    def test_google_cloud_storage(self):
        """
        Test the google cloud storage
        """

        if not self.app.is_gae():
            self.skip('Test not running in Google App Engine environment.')

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
