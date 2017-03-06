
from tests.testcase import TestCase
from edmunds.config.config import Config
import os
import edmunds.support.helpers as helpers


class TestConfig(TestCase):
    """
    Test the Config
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestConfig, self).set_up()

        random_file = helpers.random_str(10)

        # Make config file
        self.config_file = os.path.join(self.app.config.root_path, 'config/%s.py' % random_file)

        # Make env file
        self.env_file = os.path.join(self.app.config.root_path, '.env.py')
        self.env_bak_file = os.path.join(self.app.config.root_path, '.env.%s.py' % random_file)
        if os.path.exists(self.env_file):
            os.rename(self.env_file, self.env_bak_file)

        # Make env testing file
        self.env_testing_file = os.path.join(self.app.config.root_path, '.env.testing.py')
        self.env_testing_bak_file = os.path.join(self.app.config.root_path, '.env.testing.%s.py' % random_file)
        if os.path.exists(self.env_testing_file):
            os.rename(self.env_testing_file, self.env_testing_bak_file)

        # Make env production file
        self.env_production_file = os.path.join(self.app.config.root_path, '.env.production.py')
        self.env_production_bak_file = os.path.join(self.app.config.root_path, '.env.production.%s.py' % random_file)
        if os.path.exists(self.env_production_file):
            os.rename(self.env_production_file, self.env_production_bak_file)

    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestConfig, self).tear_down()

        # Remove config file
        if os.path.exists(self.config_file):
            os.remove(self.config_file)

        # Set backup env-file back
        if os.path.exists(self.env_file):
            os.remove(self.env_file)
        if os.path.exists(self.env_bak_file):
            os.rename(self.env_bak_file, self.env_file)

        # Set backup env-testing-file back
        if os.path.exists(self.env_testing_file):
            os.remove(self.env_testing_file)
        if os.path.exists(self.env_testing_bak_file):
            os.rename(self.env_testing_bak_file, self.env_testing_file)

        # Set backup env-production-file back
        if os.path.exists(self.env_production_file):
            os.remove(self.env_production_file)
        if os.path.exists(self.env_production_bak_file):
            os.rename(self.env_production_bak_file, self.env_production_file)

    def test_consistency(self):
        """
        Test the consistency of the config
        """

        data = [
            ('got.son',     'GOT_SON',      'Jon Snow 1'                    ),
            ('got.girl',    'GOT_GIRL',     'Igritte 1'                     ),
            ('got.enemy',   'GOT_ENEMY',    ('The', 'White', 'Walkers', 1)  ),
        ]

        # Test data
        for row in data:
            key, old_key, value = row

            self.assert_false(self.app.config.has(key))
            self.assert_is_none(self.app.config(key))
            self.assert_not_in(old_key, self.app.config)

            self.app.config({
                key: value
            })

            self.assert_true(self.app.config.has(key))
            self.assert_equal(value, self.app.config(key))
            self.assert_equal(value, self.app.config[old_key])

    def test_multiple(self):
        """
        Test multiple assigns at once
        """

        data = [
            ('got.son',     'GOT_SON',      'Jon Snow 2'                    ),
            ('got.girl',    'GOT_GIRL',     'Igritte 2'                     ),
            ('got.enemy',   'GOT_ENEMY',    ('The', 'White', 'Walkers', 2)  ),
        ]

        # Make update dictionary
        update = {}
        for row in data:
            key, old_key, value = row

            update[old_key] = value

            self.assert_false(self.app.config.has(key))
            self.assert_is_none(self.app.config(key))
            self.assert_not_in(old_key, self.app.config)

        # Update
        self.app.config(update)

        # Test data
        for row in data:
            key, old_key, value = row

            self.assert_true(self.app.config.has(key))
            self.assert_equal(value, self.app.config(key))
            self.assert_equal(value, self.app.config[old_key])

    def test_config_file(self):
        """
        Test config file
        """

        old_format = [
            "GOT_SON                    = 'Jon Snow 3' \n",
            "GOT_GIRL                   = 'Igritte 3' \n",
            "GOT_ENEMY                  = ('The', 'White', 'Walkers', 3) \n",
            "GOT_WINTER_IS_COMING_TO    = 'Town 3'"
        ]

        new_format = [
            "GOT = { \n",
            "   'son':      'Jon Snow 3', \n",
            "   'girl':     'Igritte 3', \n",
            "   'enemy':    ('The', 'White', 'Walkers', 3), \n",
            "   'winter': { \n",
            "       'is': { \n",
            "           'coming': { \n",
            "               'to': 'Town 3' \n",
            "           }, \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ]

        data = [
            ('got.son',                     'GOT_SON',                  'Jon Snow 3',                       ),
            ('got.girl',                    'GOT_GIRL',                 'Igritte 3',                        ),
            ('got.enemy',                   'GOT_ENEMY',                ('The', 'White', 'Walkers', 3),     ),
            ('got.winter.is.coming.to',     'GOT_WINTER_IS_COMING_TO',  'Town 3'                            ),
        ]

        # Check each format
        for format in (old_format, new_format):

            # Make config file
            if os.path.isfile(self.config_file):
                os.remove(self.config_file)
            with open(self.config_file, 'w+') as f:
                f.writelines(format)

            # Make app
            app = self.create_application()

            # Check config
            for row in data:
                key, old_key, value = row

                self.assert_true(app.config.has(key))
                self.assert_equal(value, app.config(key))
                self.assert_equal(value, app.config[old_key])

    def test_env_file(self):
        """
        Test env file
        """

        old_format = [
            "GOT_SON                    = 'Jon Snow 4' \n",
            "GOT_GIRL                   = 'Igritte 4' \n",
            "GOT_ENEMY                  = ('The', 'White', 'Walkers', 4) \n",
            "GOT_WINTER_IS_COMING_TO    = 'Town 4'"
        ]

        new_format = [
            "GOT = { \n",
            "   'son':      'Jon Snow 4', \n",
            "   'girl':     'Igritte 4', \n",
            "   'enemy':    ('The', 'White', 'Walkers', 4), \n",
            "   'winter': { \n",
            "       'is': { \n",
            "           'coming': { \n",
            "               'to': 'Town 4' \n",
            "           }, \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ]

        data = [
            ('got.son',                     'GOT_SON',                  'Jon Snow 4',                       ),
            ('got.girl',                    'GOT_GIRL',                 'Igritte 4',                        ),
            ('got.enemy',                   'GOT_ENEMY',                ('The', 'White', 'Walkers', 4),     ),
            ('got.winter.is.coming.to',     'GOT_WINTER_IS_COMING_TO',  'Town 4'                            ),
        ]

        # Check each format
        for format in (old_format, new_format):

            # Make config file
            if os.path.isfile(self.env_file):
                os.remove(self.env_file)
            with open(self.env_file, 'w+') as f:
                f.writelines(format)

            # Make app
            app = self.create_application()

            # Check config
            for row in data:
                key, old_key, value = row

                self.assert_true(app.config.has(key))
                self.assert_equal(value, app.config(key))
                self.assert_equal(value, app.config[old_key])

    def test_env_testing_file(self):
        """
        Test env testing file
        """

        old_format = [
            "GOT_SON                    = 'Jon Snow 5' \n",
            "GOT_GIRL                   = 'Igritte 5' \n",
            "GOT_ENEMY                  = ('The', 'White', 'Walkers', 5) \n",
            "GOT_WINTER_IS_COMING_TO    = 'Town 5'"
        ]

        new_format = [
            "GOT = { \n",
            "   'son':      'Jon Snow 5', \n",
            "   'girl':     'Igritte 5', \n",
            "   'enemy':    ('The', 'White', 'Walkers', 5), \n",
            "   'winter': { \n",
            "       'is': { \n",
            "           'coming': { \n",
            "               'to': 'Town 5' \n",
            "           }, \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ]

        data = [
            ('got.son',                     'GOT_SON',                  'Jon Snow 5',                       ),
            ('got.girl',                    'GOT_GIRL',                 'Igritte 5',                        ),
            ('got.enemy',                   'GOT_ENEMY',                ('The', 'White', 'Walkers', 5),     ),
            ('got.winter.is.coming.to',     'GOT_WINTER_IS_COMING_TO',  'Town 5'                            ),
        ]

        # Check each format
        for format in (old_format, new_format):

            # Make config file
            if os.path.isfile(self.env_testing_file):
                os.remove(self.env_testing_file)
            with open(self.env_testing_file, 'w+') as f:
                f.writelines(format)

            # Make app
            app = self.create_application()

            # Check config
            for row in data:
                key, old_key, value = row

                self.assert_true(app.config.has(key))
                self.assert_equal(value, app.config(key))
                self.assert_equal(value, app.config[old_key])

    def test_env_testing_test_file(self):
        """
        Test env testing test file
        """

        old_format = [
            "GOT_SON                    = 'Jon Snow 6' \n",
            "GOT_GIRL                   = 'Igritte 6' \n",
            "GOT_ENEMY                  = ('The', 'White', 'Walkers', 6) \n",
            "GOT_WINTER_IS_COMING_TO    = 'Town 6'"
        ]

        new_format = [
            "GOT = { \n",
            "   'son':      'Jon Snow 6', \n",
            "   'girl':     'Igritte 6', \n",
            "   'enemy':    ('The', 'White', 'Walkers', 6), \n",
            "   'winter': { \n",
            "       'is': { \n",
            "           'coming': { \n",
            "               'to': 'Town 6' \n",
            "           }, \n",
            "       }, \n",
            "   }, \n",
            "} \n",
        ]

        data = [
            ('got.son',                     'GOT_SON',                  'Jon Snow 6',                       ),
            ('got.girl',                    'GOT_GIRL',                 'Igritte 6',                        ),
            ('got.enemy',                   'GOT_ENEMY',                ('The', 'White', 'Walkers', 6),     ),
            ('got.winter.is.coming.to',     'GOT_WINTER_IS_COMING_TO',  'Town 6'                            ),
        ]

        # Check each format
        for format in (old_format, new_format):

            # Make config file
            self.write_config(format)

            # Make app
            app = self.create_application()

            # Check config
            for row in data:
                key, old_key, value = row

                self.assert_true(app.config.has(key))
                self.assert_equal(value, app.config(key))
                self.assert_equal(value, app.config[old_key])

    def test_merging_and_priority(self):
        """
        Test merging and priority of config
        """

        # Make env testing test file
        self.write_config([
            "GOT = { \n",
            "   'son': 'Jon Snow 7', \n",
            "   'priority': { \n",
            "       'first': 1, \n",
            "   } \n",
            "} \n",
        ]);

        # Make env testing file
        with open(self.env_testing_file, 'w+') as f:
            f.writelines([
                "GOT = { \n",
                "   'girl': 'Igritte 7', \n",
                "   'priority': { \n",
                "       'first': 2, \n",
                "       'second': 2, \n",
                "   } \n",
                "} \n",
            ])

        # Make env file
        with open(self.env_file, 'w+') as f:
            f.writelines([
                "GOT = { \n",
                "   'enemy': ('The', 'White', 'Walkers', 7), \n",
                "   'priority': { \n",
                "       'first': 3, \n",
                "       'second': 3, \n",
                "       'third': 3, \n",
                "   } \n",
                "} \n",
            ])

        # Make config file
        with open(self.config_file, 'w+') as f:
            f.writelines([
                "GOT = { \n",
                "   'weapon': 'Dragon Glass 7', \n",
                "   'priority': { \n",
                "       'first': 4, \n",
                "       'second': 4, \n",
                "       'third': 4, \n",
                "       'fourth': 4, \n",
                "   } \n",
                "} \n",
            ])

        data = [
            ('got.son',                 'GOT_SON',              'Jon Snow 7',                   ),
            ('got.girl',                'GOT_GIRL',             'Igritte 7',                    ),
            ('got.enemy',               'GOT_ENEMY',            ('The', 'White', 'Walkers', 7), ),
            ('got.weapon',              'GOT_WEAPON',           'Dragon Glass 7',               ),
            ('got.priority.first',      'GOT_PRIORITY_FIRST',   1,                              ),
            ('got.priority.second',     'GOT_PRIORITY_SECOND',  2,                              ),
            ('got.priority.third',      'GOT_PRIORITY_THIRD',   3,                              ),
            ('got.priority.fourth',     'GOT_PRIORITY_FOURTH',  4,                              ),
        ]

        # Make app
        app = self.create_application()

        # Check config
        for row in data:
            key, old_key, value = row

            self.assert_true(app.config.has(key))
            self.assert_equal(value, app.config(key))
            self.assert_equal(value, app.config[old_key])

    def test_file_priority(self):
        """
        Test priority of config
        """

        key = 'got.season'
        old_key = 'GOT_SEASON'

        # Make config file
        with open(self.config_file, 'w+') as f:
            f.write("%s = %d" % (old_key, 1))

        # Make app
        app = self.create_application()

        # Check config
        self.assert_true(app.config.has(key))
        self.assert_equal(1, app.config(key))
        self.assert_equal(1, app.config[old_key])

        # Make env file
        with open(self.env_file, 'w+') as f:
            f.write("%s = %d" % (old_key, 2))

        # Make app
        app = self.create_application()

        # Check config
        self.assert_true(app.config.has(key))
        self.assert_equal(2, app.config(key))
        self.assert_equal(2, app.config[old_key])

        # Make env testing file
        with open(self.env_testing_file, 'w+') as f:
            f.write("%s = %d" % (old_key, 3))

        # Make app
        app = self.create_application()

        # Check config
        self.assert_true(app.config.has(key))
        self.assert_equal(3, app.config(key))
        self.assert_equal(3, app.config[old_key])

        # Make env testing test file
        self.write_config("%s = %d" % (old_key, 4));

        # Make app
        app = self.create_application()

        # Check config
        self.assert_true(app.config.has(key))
        self.assert_equal(4, app.config(key))
        self.assert_equal(4, app.config[old_key])

    def test_setting_environment(self):
        """
        Test setting the environment
        """

        value = 'nice'
        str_value = "'nice'"
        key = 'got.niveau'
        old_key = 'GOT_NIVEAU'

        # Make env file
        with open(self.env_production_file, 'w+') as f:
            f.write("%s = %s\n" % (old_key, str_value))

        # Make app
        app = self.create_application(environment='production')

        # Check config
        self.assert_true(app.config.has(key))
        self.assert_equal(value, app.config(key))
        self.assert_equal(value, app.config[old_key])
