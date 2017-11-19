
from tests.testcase import TestCase
from werkzeug.contrib.cache import FileSystemCache
from edmunds.cache.drivers.file import File
import os


class TestFile(TestCase):
    """
    Test the File
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestFile, self).set_up()

        self.prefix = self.rand_str(20) + '.'
        self.storage_directory = os.sep + 'storage' + os.sep
        self.cache_directory ='cache'
        self.clear_paths = []

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestFile, self).tear_down()

        # Remove all profiler files
        for directory in self.clear_paths:
            for root, subdirs, files in os.walk(directory):
                for file in files:
                    if file.startswith(self.prefix):
                        os.remove(os.path.join(root, file))

    def test_file(self):
        """
        Test the file
        """

        # Write config
        self.write_config([
            "from edmunds.storage.drivers.file import File as StorageFile \n",
            "from edmunds.cache.drivers.file import File \n",
            "APP = { \n",
            "   'storage': { \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'file',\n",
            "               'driver': StorageFile,\n",
            "               'directory': '%s',\n" % self.storage_directory,
            "               'prefix': '%s',\n" % self.prefix,
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "   'cache': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'file',\n",
            "               'driver': File,\n",
            "               'directory': '%s',\n" % self.cache_directory,
            "               'threshold': 500,\n",
            "               'default_timeout': 300,\n",
            "               'mode': 0o600,\n",
            "           }, \n",
            "           { \n",
            "               'name': 'file2',\n",
            "               'driver': File,\n",
            "               'directory': '/%s',\n" % self.cache_directory,
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
            ])

        # Create app
        app = self.create_application()

        driver = app.cache()
        self.assert_is_instance(driver, File)
        self.assert_is_instance(driver, FileSystemCache)

        self.clear_paths.append(driver._path)
