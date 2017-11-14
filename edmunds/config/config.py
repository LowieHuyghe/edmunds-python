
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
        flat_key = self._get_flat_key(key)

        # Always loop sorted and then reversed over keys
        # self['APP_INFO'] has priority over self['APP']['info']
        for key in reversed(sorted(self.keys())):
            current_flat_key = self._get_flat_key(key)
            if current_flat_key == flat_key:
                return self[key]

            flat_key_prefix = '%s_' % current_flat_key
            if flat_key == flat_key_prefix or not flat_key.startswith(flat_key_prefix):
                continue
            key_parts = self._get_key_parts(flat_key[len(flat_key_prefix):])

            found_value = True
            value = self[key]
            for key_part in key_parts:
                # Process dict as value
                if isinstance(value, dict):
                    found_dict_value = False
                    for dict_key in reversed(sorted(value)):
                        if key_part == self._get_flat_key(dict_key):
                            value = value[dict_key]
                            found_dict_value = True
                            break
                    if found_dict_value:
                        continue
                # Process list as value
                elif isinstance(value, list):
                    if key_part.isdigit() and int(key_part) < len(value):
                        value = value[int(key_part)]
                        continue
                # Did not found
                found_value = False
                break

            if found_value:
                return value

        return default

    def has(self, key):
        """
        Check if has value for this key
        :param key:     The key
        :type key:      str
        :return:        Value
        """
        check = {}
        return self._get(key, default=check) is not check

    def _get_flat_key(self, key):
        """
        Get key parts
        :param key:     The key
        :type key:      str
        :return:        Flat key
        :rtype:         str
        """
        return key.replace('.', '_').upper()

    def _get_key_parts(self, key):
        """
        Get key parts
        :param key:     The key
        :type key:      str
        :return:        Key parts
        :rtype:         list(str)
        """
        flat_key = self._get_flat_key(key)
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

    def from_object(self, obj):
        """
        Updates the values from the given object.
        :param obj: an import name or object
        """
        backup = self.copy()
        self.clear()

        super(Config, self).from_object(obj)
        new = self.copy()
        self.clear()

        self.update(self._deep_merge(backup, new))

    def from_mapping(self, *mapping, **kwargs):
        """
        Updates the config like :meth:`update` ignoring items with non-upper keys.
        """
        backup = self.copy()
        self.clear()

        result = super(Config, self).from_mapping(*mapping, **kwargs)
        new = self.copy()
        self.clear()

        self.update(self._deep_merge(backup, new))

        return result

    def _deep_merge(self, original, new):
        """
        Deep merge item as if it would have been overriden
        :param original:  First entry
        :param new:  Second entry
        :return:        Merged value
        """

        if isinstance(original, dict) and isinstance(new, dict):
            original = original.copy()
            for new_key in new:
                new_value = new[new_key]
                original_value = original[new_key] if new_key in original else None

                original[new_key] = self._deep_merge(original_value, new_value)
            return original

        return new
