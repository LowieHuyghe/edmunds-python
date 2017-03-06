
from tests.testcase import TestCase
import edmunds.support.helpers as helpers
import os


class TestBlackfireIo(TestCase):
    """
    Test the BlackfireIo
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestBlackfireIo, self).set_up()

        self.prefix = helpers.random_str(20) + '.'
        self.storage_directory = os.sep + 'storage' + os.sep
        self.profs_directory = os.sep + 'profs' + os.sep
        self.clear_paths = []

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestBlackfireIo, self).tear_down()

        # Remove all profiler files
        for directory in self.clear_paths:
            for root, subdirs, files in os.walk(directory):
                for file in files:
                    if file.startswith(self.prefix):
                        os.remove(os.path.join(root, file))

    def test_blackfire_io(self):
        """
        Test the BlackfireIo
        """

        # Write config
        self.write_config([
            "from edmunds.storage.drivers.file import File as StorageFile \n",
            "from edmunds.profiler.drivers.blackfireio import BlackfireIo \n",
            "APP = { \n",
            "   'debug': True, \n",
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
            "   'profiler': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'blackfireio',\n",
            "               'driver': BlackfireIo,\n",
            "               'directory': '%s',\n" % self.profs_directory,
            "               'prefix': '%s',\n" % self.prefix,
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app and fetch stream
        app = self.create_application()
        directory = app.fs()._get_processed_path(self.profs_directory)
        self.clear_paths.append(directory)
        self.assert_equal(self.profs_directory, app.config('app.profiler.instances')[0]['directory'])
        self.assert_equal(self.prefix, app.config('app.profiler.instances')[0]['prefix'])

        # Add route
        rule = '/' + helpers.random_str(20)
        @app.route(rule)
        def handleRoute():
            return ''

        with app.test_client() as c:

            # Count profiler files
            prof_files = []
            for root, subdirs, files in os.walk(directory):
                for file in files:
                    if file.startswith(self.prefix):
                        prof_files.append(os.path.join(root, file))

            self.assert_equal(0, len(prof_files))

            # Call route
            c.get(rule)

            # Count profiler files
            prof_files = []
            for root, subdirs, files in os.walk(directory):
                for file in files:
                    if file.startswith(self.prefix):
                        prof_files.append(os.path.join(root, file))

            self.assert_equal(1, len(prof_files))
