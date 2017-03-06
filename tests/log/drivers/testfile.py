
from tests.testcase import TestCase
import edmunds.support.helpers as helpers
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

        self.prefix = helpers.random_str(20) + '.'
        self.storage_directory = os.sep + 'storage' + os.sep
        self.logs_directory = os.sep + 'logs' + os.sep
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

        info_string = 'info_%s' % helpers.random_str(20)
        warning_string = 'warning_%s' % helpers.random_str(20)
        error_string = 'error_%s' % helpers.random_str(20)

        # Write config
        self.write_config([
            "from edmunds.storage.drivers.file import File as StorageFile \n",
            "from edmunds.log.drivers.file import File \n",
            "from logging import WARNING \n",
            "APP = { \n",
            "   'debug': False, \n",
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
            "   'log': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'file',\n",
            "               'driver': File,\n",
            "               'directory': '%s',\n" % self.logs_directory,
            "               'prefix': '%s',\n" % self.prefix,
            "               'level': WARNING,\n"
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()
        directory = app.fs()._get_processed_path(self.logs_directory)
        self.clear_paths.append(directory)
        self.assert_equal(self.logs_directory, app.config('app.log.instances')[0]['directory'])
        self.assert_equal(self.prefix, app.config('app.log.instances')[0]['prefix'])

        # Add route
        rule = '/' + helpers.random_str(20)
        @app.route(rule)
        def handleRoute():
            app.logger.info(info_string)
            app.logger.warning(warning_string)
            app.logger.error(error_string)
            return ''

        with app.test_client() as c:

            # Check file
            self.assert_false(self._is_in_log_files(app, directory, info_string))
            self.assert_false(self._is_in_log_files(app, directory, warning_string))
            self.assert_false(self._is_in_log_files(app, directory, error_string))

            # Call route
            c.get(rule)

            # Check file
            self.assert_false(self._is_in_log_files(app, directory, info_string))
            self.assert_true(self._is_in_log_files(app, directory, warning_string))
            self.assert_true(self._is_in_log_files(app, directory, error_string))


    def _is_in_log_files(self, app, directory, string, starts_with = None):
        """
        Check if string is in log files
        :param app:             The app to work with
        :type  app:             Application
        :param directory:       The directory to check
        :type  directory:       str
        :param string:          The string to check
        :type  string:          str
        :param starts_with:     The filename starts with
        :type  starts_with:     str
        :return:                Is in file
        :rtype:                 boolean
        """

        if starts_with is None:
            starts_with = self.prefix

        # Fetch files
        log_files = []
        for root, subdirs, files in os.walk(directory):
            for file in files:
                if file.startswith(starts_with):
                    log_files.append(os.path.join(self.logs_directory, file))

        # Check files
        occurs = False
        for file in log_files:
            f = app.fs().read_stream(file)

            try:
                if string in f.read():
                    occurs = True
                    break
            finally:
                f.close()

        return occurs