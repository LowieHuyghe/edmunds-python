
from tests.testcase import TestCase
import os


class TestConfig(TestCase):
    """
    Test the Config
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestConfig, self).set_up()

        random_file = self.rand_str(10)

        # Make edmunds config file
        edmunds_config_dir = os.path.abspath(os.path.join(self.directory(), os.pardir, 'config'))
        self.edmunds_config_file = os.path.join(self.app.config.root_path, os.path.join(edmunds_config_dir, '%s.py' % random_file))

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestConfig, self).tear_down()

        # Remove edmunds config file
        if os.path.exists(self.edmunds_config_file):
            os.remove(self.edmunds_config_file)

    def test_edmunds_config_file(self):
        """
        Test edmunds config file
        :return:    void
        """

        new_format = [
            "GOT = { \n",
            "   'son':      'Jon Snow 1', \n",
            "   'girl':     'Igritte 1', \n",
            "   'enemy':    ('The', 'White', 'Walkers', 1), \n",
            "   'winter': { \n",
            "       'is': { \n",
            "           'coming': { \n",
            "               'to': 'Town 1' \n",
            "           }, \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ]

        data = [
            ('got.son',                     'Jon Snow 1',                       ),
            ('got.girl',                    'Igritte 1',                        ),
            ('got.enemy',                   ('The', 'White', 'Walkers', 1),     ),
            ('got.winter.is.coming.to',     'Town 1'                            ),
        ]

        # Make config file
        with open(self.edmunds_config_file, 'w+') as f:
            f.writelines(new_format)

        # Make app
        app = self.create_application()

        # Check config
        for row in data:
            key, value = row

            self.assert_true(app.config.has(key))
            self.assert_equal(value, app.config(key))

    def test_extra_config_dir(self):
        """
        Test extra config dir
        :return:    void
        """

        extra_config_dir = self.temp_dir(only_path=True)
        os.mkdir(extra_config_dir)
        self.assert_true(os.path.isdir(extra_config_dir))
        extra_config_file = os.path.join(extra_config_dir, '%s.py' % self.rand_str(10))

        new_format = [
            "GOT = { \n",
            "   'son':      'Jon Snow 2', \n",
            "   'girl':     'Igritte 2', \n",
            "   'enemy':    ('The', 'White', 'Walkers', 2), \n",
            "   'winter': { \n",
            "       'is': { \n",
            "           'coming': { \n",
            "               'to': 'Town 2' \n",
            "           }, \n",
            "       }, \n",
            "   }, \n",
            "} \n",
            "APP = { \n",
            "   'localization': { \n",
            "       'fallback': 'en', \n",
            "       'locale': { \n",
            "           'supported': [ \n",
            "              'en' \n",
            "           ] \n",
            "       } \n",
            "   } \n",
            "} \n",
        ]

        data = [
            ('got.son',                     'Jon Snow 2',                       ),
            ('got.girl',                    'Igritte 2',                        ),
            ('got.enemy',                   ('The', 'White', 'Walkers', 2),     ),
            ('got.winter.is.coming.to',     'Town 2'                            ),
        ]

        # Make config file
        with open(extra_config_file, 'w+') as f:
            f.writelines(new_format)

        # Make app and load config
        app = self.create_application(config_dirs=[extra_config_dir])

        # Check config
        for row in data:
            key, value = row

            self.assert_true(app.config.has(key))
            self.assert_equal(value, app.config(key))
