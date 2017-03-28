
from tests.testcase import TestCase
from edmunds.storage.drivers.file import File


class TestStorage(TestCase):
    """
    Test the Storage
    """

    def test_loading_and_fs(self):
        """
        Test loading and fs function
        :return:    void
        """

        # Write config
        self.write_config([
            "from edmunds.storage.drivers.file import File \n",
            "from logging import WARNING \n",
            "APP = { \n",
            "   'storage': { \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'file',\n",
            "               'driver': File,\n",
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

        # Fs function
        self.assert_is_instance(app.fs(), File)
        self.assert_is_instance(app.fs('file'), File)
        with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
            app.fs('file2')
