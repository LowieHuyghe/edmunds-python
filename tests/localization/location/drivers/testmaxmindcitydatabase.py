
from tests.testcase import TestCase
import os


class TestMaxMindCityDatabase(TestCase):
    """
    Test the MaxMind City Database driver
    """

    def set_up(self):
        """
        Set up
        :return:    void 
        """
        super(TestMaxMindCityDatabase, self).set_up()

        self.prefix = self.rand_str(20) + '.'
        self.storage_directory = os.sep + 'storage' + os.sep
        self.config = [
            "from edmunds.localization.location.drivers.maxmindcitydatabase import MaxMindCityDatabase \n",
            "from edmunds.storage.drivers.file import File as StorageFile \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'location': { \n",
            "           'enabled': True, \n",
            "           'instances': [ \n",
            "               { \n",
            "                   'name': 'maxmindcitydb',\n",
            "                   'driver': MaxMindCityDatabase,\n",
            "                   'database': 'database.mmdb',\n",
            "               }, \n",
            "           ], \n",
            "       }, \n",
            "   }, \n",
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
            "} \n",
        ]

    def test_missing_params(self):
        """
        Test missing params
        :return:    void
        """

        remove_lines = [10]

        # Loop lines that should be individually removed
        for remove_line in remove_lines:
            new_config = self.config[:]
            del new_config[remove_line]

            self.write_config(new_config)

            # Create app
            app = self.create_application()

            # Error on loading of config
            with self.assert_raises_regexp(RuntimeError, 'missing some configuration'):
                app.localization().location()

    def test_insights(self):
        """
        Test insights
        :return:    void
        """

        # Write config and create application
        self.write_config(self.config)
        app = self.create_application()

        # Fetch driver
        with self.assert_raises_regexp(IOError, 'No such file or directory'):
            app.localization().location()
