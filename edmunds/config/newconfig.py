
from flask.config import Config as FlaskConfig
import os
import re


class Config(FlaskConfig):
    """
    Config module
    """

    def __call__(self, key, default=None):
        """
        Get the value
        :param key:     The key
        :type key:      str
        :param default: The default value if it does not exist
        :type default:  mixed
        :return:        Value
        """
        return self._get(key, default=default)

    def _get(self, key, default=None):
        """
        Get the value for this key
        :param key:     The key
        :type key:      str
        :param default: The default value if it does not exist
        :type default:  mixed
        :return:        Value
        """
        flat_key = key.replace('.', '_').upper()
        key_parts = flat_key.split('_')

        result = self._get_recursive(self, key_parts)
        if result is not None:
            return result

        return default

    def _get_recursive(self, remaining, key_parts):
        """
        Get value in a recursive manner
        :param remaining:   The remaining value
        :param key_parts:   The key parts
        :return:            The value
        """

        # There are no key-parts to look for anymore
        if not key_parts:
            return remaining

        # There are still key-parts but no value to dig deeper into
        if not isinstance(remaining, list) and not isinstance(remaining, dict):
            return None

        # Define stuff
        key_part = key_parts[0]
        key_parts = key_parts[1:]
        result = None

        # Make sure we are looping over keys
        for i in remaining if not isinstance(remaining, list) else range(0, len(remaining)):
            key = '%s' % i

            # Get recursive result
            if key.upper() == key_part:
                new_result = self._get_recursive(remaining[key], key_parts)
            elif key.upper().startswith(key_part):
                new_key = key[len(key_part):].lstrip('_')
                new_remaining = {new_key: remaining[key]}
                new_result = self._get_recursive(new_remaining, key_parts)
            else:
                continue
            if new_result is None:
                continue

            # If result is solid, and not diggable: return it
            if not isinstance(new_result, list) and not isinstance(new_result, dict):
                return new_result

            # Merge new_result in the result
            if result is None:
                result = new_result
            elif isinstance(new_result, list):
                if isinstance(result, list):
                    result = result + new_result
                else:
                    result = result.copy()
                    result.update(dict(enumerate(new_result)))
            else:
                if isinstance(result, list):
                    result = dict(enumerate(result))
                else:
                    result = result.copy()
                result.update(new_result)

        # Return result
        return result

    def has(self, key):
        """
        Check if has value for this key
        :param key:     The key
        :type key:      str
        :return:        Value
        """
        flat_key = key.replace('.', '_').upper()
        key_parts = flat_key.split('_')

        return self._has_recursive(self, key_parts)

    def _has_recursive(self, remaining, key_parts):
        """
        Check has value in a recursive manner
        :param remaining:   The remaining value
        :param key_parts:   The key parts
        :return:            The value
        """

        # There are no key-parts to look for anymore
        if not key_parts:
            return remaining is not None

        # There are still key-parts but no value to dig deeper into
        if not isinstance(remaining, list) and not isinstance(remaining, dict):
            return False

        # Define stuff
        key_part = key_parts[0]
        key_parts = key_parts[1:]

        # Make sure we are looping over keys
        for i in remaining if not isinstance(remaining, list) else range(0, len(remaining)):
            key = '%s' % i

            # Check has recursive
            if key.upper() == key_part:
                if self._has_recursive(remaining[key], key_parts):
                    return True
            elif key.upper().startswith(key_part):
                new_key = key[len(key_part):].lstrip('_')
                new_remaining = {new_key: remaining[key]}
                if self._has_recursive(new_remaining, key_parts):
                    return True

        # Found nothing
        return False

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
