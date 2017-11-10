
from flask.config import Config as FlaskConfig
import os
import re


class Config(FlaskConfig):
    """
    Config module
    """

    def has(self, key):
        """
        Check if has
        :param key: The key
        :type key:  str
        :return:    Has
        :rtype:     bool
        """
        flat_key = self._get_flat_key(key)

        # Value exists
        if flat_key in self:
            return True

        # Namespace maybe?
        namespace = '%s_' % flat_key
        for i in self:
            if i.startswith(namespace):
                return True

        return False

    def __call__(self, mixed, default=None):
        """
        Get the value
        :param mixed:   Key of update-dict
        :type mixed:    str|dict
        :param default: The default value if it does not exist
        :type default:  mixed
        :return:        Value
        """
        if isinstance(mixed, dict):
            return self.update(mixed)
        else:
            return self._get(mixed, default=default)

    def _get(self, key, default=None):
        """
        Get the value for this key
        :param key:     The key
        :type key:      str
        :param default: The default value if it does not exist
        :type default:  mixed
        :return:        Value
        """
        flat_key = self._get_flat_key(key)

        # Value exists
        if flat_key in self:
            return self[flat_key]

        # Check namespace
        namespace = self.get_namespace('%s_' % flat_key, trim_namespace=True)
        if namespace:
            return self._expand(namespace)

        # Default
        return default

    def __setitem__(self, key, value):
        """
        Set item
        :param key:     Key
        :param value:   Value
        :return:        void
        """
        flat_key = self._get_flat_key(key)
        result = self._flatten(value, initial_key=flat_key)

        for i in list(self.keys()):
            if i.startswith(flat_key):
                del self[i]

        self.update(result)

    def _get_flat_key(self, key):
        """
        Get flat key
        :param key:     The key
        :type key:      str
        :return:        Processed key
        :rtype:         str
        """
        return key.replace('.', '_').upper()

    def _expand(self, flattie):
        """
        Expand flat dictionary
        :param flattie: The flattie
        :type flattie:  dict
        :return:        The expanded flattie
        :rtype:         dict
        """

        keys = list(flattie.keys())
        keys.sort()

        expanded = None

        for key in keys:
            value = flattie[key]
            flat_key = self._get_flat_key(key).lower()
            key_parts = flat_key.split('_')

            expanded = self._expand_loop(key_parts, value, expanded)

        return expanded

    def _expand_loop(self, keys, value, expanded=None):
        """
        Get expanded namespace loop
        :param keys:        The keys
        :param value:       The value
        :param expanded:    The expanded dict/list
        :return:            The expanded dict/list
        """
        key = keys[0]
        if key.isdigit():
            key = int(key)
        next_keys = keys[1:]

        if expanded is None:
            if isinstance(key, int) and key == 0:
                expanded = []
            else:
                expanded = {}
        elif isinstance(expanded, list):
            if not isinstance(key, int) or key != len(expanded):
                expanded = dict(enumerate(expanded))

        if not next_keys:
            set_value = value
        elif key in expanded:
            set_value = self._expand_loop(next_keys, value, expanded[key])
        else:
            set_value = self._expand_loop(next_keys, value)

        if isinstance(expanded, list):
            expanded.append(set_value)
        else:
            expanded[key] = set_value

        return expanded

    def _flatten(self, expanded, flat=None, initial_key=None):
        """
        Flatten an expanded dict or list
        :param expanded:    Expanded dict or list
        :param flat:        Flat dictionary result
        :param initial_key: Initial key
        :return:            Flat dictionary
        :rtype:             dict
        """
        if flat is None:
            flat = {}

        if isinstance(expanded, list) or isinstance(expanded, dict):
            if isinstance(expanded, list):
                loopediloop = range(0, len(expanded))
            else:
                loopediloop = expanded

            for i in loopediloop:
                key = '%s' % i
                if initial_key:
                    key = '%s_%s' % (initial_key, key)

                self._flatten(expanded[i], flat, initial_key=key)
        elif initial_key is None and not flat:
            return expanded
        else:
            flat[initial_key.upper()] = expanded

        return flat

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
            self['APP_ENV'] = os.environ.get('APP_ENV')

        # Check if environment set
        if 'APP_ENV' not in self or not self['APP_ENV']:
            raise RuntimeError('App environment is not set.')

        # Lower the environment value
        self['APP_ENV'] = self['APP_ENV'].lower()

        # Load environment specific .env
        env_environment_file_path = os.path.join(self.root_path, '.env.%s.py' % self['APP_ENV'])
        if os.path.isfile(env_environment_file_path):
            self.from_pyfile(env_environment_file_path)

        # If testing, load specific test .env specifically meant for testing purposes
        if self['APP_ENV'] == 'testing':
            env_environment_test_file_path = os.path.join(self.root_path, '.env.%s.test.py' % self['APP_ENV'])
            if os.path.isfile(env_environment_test_file_path):
                self.from_pyfile(env_environment_test_file_path)
