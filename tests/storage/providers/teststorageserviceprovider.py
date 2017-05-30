
from tests.testcase import TestCase
from edmunds.storage.drivers.file import File
from edmunds.storage.storagemanager import StorageManager


class TestStorageServiceProvider(TestCase):
    """
    Test the Storage Service Provider
    """

    def test_register(self):
        """
        Test register
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

        # Test extension
        self.assert_in('edmunds.storage', app.extensions)
        self.assert_is_not_none(app.extensions['edmunds.storage'])
        self.assert_is_instance(app.extensions['edmunds.storage'], StorageManager)
        self.assert_is_instance(app.extensions['edmunds.storage'].get('file'), File)
        with self.assert_raises_regexp(RuntimeError, '[Nn]o instance'):
            app.extensions['edmunds.storage'].get('file2')
