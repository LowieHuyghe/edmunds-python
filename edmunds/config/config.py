
from flask.config import Config as FlaskConfig
import os
import re


class Config(FlaskConfig):
    """
    Config module
    """

    def __init__(self, root_path, defaults=None):
        """
        Initiate the dictionary
        :param root_path:   The root path
        :type  root_path:   str
        :param defaults:    The default
        :type  defaults:    mixed
        """

        super(Config, self).__init__(root_path, defaults)

        self.loaded_config = []

    def __call__(self, mixed, default=None):
        """
        Get a value or update some values
        :param mixed:       Key of dictionary
        :type mixed:        dict|key
        :param default:     The default value when fetching a value
        :type  default:     mixed
        :return:            Respectively the value and None
        :rtype:             mixed|None
        """

        # Update dictionary
        if isinstance(mixed, dict):
            processed_dict = {}

            for key in mixed:
                processed_key = self._getProcessKey(key)
                processed_dict[processed_key] = mixed[key]

            return self.update(processed_dict)

        # Get value
        else:
            if not self.has(mixed):
                return default

            processed_key = self._getProcessKey(mixed)
            return self[processed_key]

    def has(self, key):
        """
        Check if has key
        :param key:     The key
        :type  key:     str
        :return:        Has key?
        :rtype:         boolean
        """

        processed_key = self._getProcessKey(key)
        return processed_key in self

    def _getProcessKey(self, key):
        """
        Process the given key
        :param key:     The key to process
        :type  key:     str
        :return:        The processed key
        :rtype:         str
        """

        return '_'.join(key.split('.')).upper()

    def load_all(self, config_dirs):
        """
        Load all config files
        :param config_dirs:     Configuration directories
        :type  config_dirs:     list
        """

        # Load configuration in order
        # Newly loaded overwrites current values
        self._load_config(config_dirs)
        self._load_env()

    def _load_config(self, config_dirs):
        """
        Load the configuration
        :param config_dirs:     Configuration directories
        :type  config_dirs:     list
        """

        for config_dir in config_dirs:
            for root, subdirs, files in os.walk(config_dir):
                for file in files:
                    if not re.match(r'^[a-zA-Z0-9]+\.py$', file):
                        continue

                    file_name = os.path.join(self.root_path, config_dir, file)

                    self.from_pyfile(file_name)

    def _load_env(self):
        """
        Load environment config
        """

        # Load .env file
        env_file_path = os.path.join(self.root_path, '.env.py')
        if os.path.isfile(env_file_path):
            self.from_pyfile(env_file_path)

        # Overwrite with APP_ENV value set in environment
        if 'APP_ENV' in os.environ:
            self({
                'app.env': os.environ.get('APP_ENV')
            })

        # Check if environment set
        if not self.has('app.env') or not self('app.env'):
            raise RuntimeError('App environment is not set.')

        # Load environment specific .env
        env_environment_file_path = os.path.join(self.root_path, '.env.%s.py' % self('app.env').lower())
        if os.path.isfile(env_environment_file_path):
            self.from_pyfile(env_environment_file_path)

        # If testing, load specific test .env specifically meant for testing purposes
        if self('app.env').lower() == 'testing':
            env_environment_test_file_path = os.path.join(self.root_path, '.env.%s.test.py' % self('app.env').lower())
            if os.path.isfile(env_environment_test_file_path):
                self.from_pyfile(env_environment_test_file_path)

        # Lower the environment value
        self({
            'app.env': self('app.env').lower()
        })

    def from_pyfile(self, filename, silent=False):
        """
        Load from py-file
        :param filename:    File to load
        :type  filename:    str
        :param silent:      Die silently when config-file does not exist
        :type  silent:      bool
        :return:            Success
        :rtype:             bool
        """

        # Backup of original
        processed_original = self._flatten_dict(self)
        self.clear()

        # Load new
        success = super(Config, self).from_pyfile(filename, silent)
        if success:
            # Flatten new list
            processed_new = self._flatten_dict(self)
            self.clear()

            # Set back original and overwrite with new
            self.update(processed_original)
            self.update(processed_new)

        else:
            # Set back original
            self.update(processed_original)

        return success

    def _flatten_dict(self, new, processed_new=None, prefix_key=''):
        """
        Flatten the dictionary to dictionary with only one level
        :param new:             The given dictionary
        :type  new:             dict
        :param processed_new:   The processed dictionary
        :type  processed_new:   dict
        :param prefix_key:      The prefix key
        :type  prefix_key:      str
        :return:                The processed dictionary
        :rtype:                 dict
        """

        if processed_new is None:
            processed_new = {}

        for key in new:
            value = new[key]
            key_str = '%s' % key

            if isinstance(value, dict):
                self._flatten_dict(value, processed_new, prefix_key + key_str.upper() + '_')
            else:
                processed_new[prefix_key + key_str.upper()] = value

        return processed_new
