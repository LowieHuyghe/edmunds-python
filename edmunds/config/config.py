
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
        key_parts = self._get_key_parts(key)
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
        key_parts = self._get_key_parts(key)
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

    def _get_key_parts(self, key):
        """
        Get key parts
        :param key:     The key
        :type key:      str
        :return:        Key parts
        :rtype:         list(str)
        """
        flat_key = key.replace('.', '_').upper()
        return flat_key.split('_')

    def from_pydir(self, config_dir):
        """
        Load the configuration in directory
        :param config_dir:  Configuration directory
        :type  config_dir:  str
        """
        for root, subdirs, files in os.walk(config_dir):
            for file in files:
                if not re.match(r'^[a-zA-Z0-9]+\.py$', file):
                    continue

                file_name = os.path.join(self.root_path, config_dir, file)

                self.from_pyfile(file_name)
