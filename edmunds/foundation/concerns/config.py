
import os


class Config(object):
    """
    This class concerns config code for Application to extend from
    """

    def _init_config(self, config_dirs=None):
        """
        Initiate the configuration
        :param config_dirs:     Configuration directories
        :type  config_dirs:     list
        """

        # Configuration directories
        if config_dirs is None:
            # edmunds/edmunds/foundation/concerns
            edmunds_config_dir = os.path.dirname(os.path.realpath(__file__))
            # edmunds/edmunds/foundation/concerns/../../../config
            edmunds_config_dir = os.path.join(edmunds_config_dir, os.pardir, os.pardir, os.pardir, 'config')
            # edmunds/config
            edmunds_config_dir = os.path.abspath(edmunds_config_dir)

            config_dirs = [
                edmunds_config_dir,
                'config',
            ]

        # Load config-dirs
        for config_dir in config_dirs:
            self.config.from_pydir(config_dir)
        # Load env files
        self._load_env()

    def _load_env(self):
        """
        Load environment config
        """
        # Load .env file
        env_file_path = '.env.py'
        if os.path.isfile(os.path.join(self.config.root_path, '.env.py')):
            self.config.from_pyfile(env_file_path)

        # Overwrite with APP_ENV value set in environment
        if 'APP_ENV' in os.environ:
            self.config['APP_ENV'] = os.environ.get('APP_ENV')

        # Check if environment set
        environment = self.config('app.env')
        if not environment:
            raise RuntimeError('App environment is not set.')

        # Lower the environment value
        self.config['APP_ENV'] = environment.lower()
        environment = self.config('app.env')

        # Load environment specific .env
        env_environment_file_path = '.env.%s.py' % environment
        if os.path.isfile(os.path.join(self.config.root_path, env_environment_file_path)):
            self.config.from_pyfile(env_environment_file_path)

        # If testing, load specific test .env specifically meant for testing purposes
        if environment == 'testing':
            env_environment_test_file_path = '.env.%s.test.py' % environment
            if os.path.isfile(os.path.join(self.config.root_path, env_environment_test_file_path)):
                self.config.from_pyfile(env_environment_test_file_path)
