
from flask.config import Config as FlaskConfig


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

        # Value exists
        if flat_key in self:
            return self[flat_key]

        # Check namespace
        namespace = self.get_namespace('%s_' % flat_key, trim_namespace=True)
        if namespace:
            return self._get_expanded_namespace(namespace)

        # Default
        return default

    def _get_flat_key(self, key):
        """
        Get flat key
        :param key:     The key
        :type key:      str
        :return:        Processed key
        :rtype:         str
        """
        return key.replace('.', '_').upper()

    def _get_expanded_namespace(self, namespace):
        """
        Process namespace
        :param namespace:   The namespace
        :type namespace:    dict
        :return:            The expanded namespace
        :rtype:             dict
        """

        keys = list(namespace.keys())
        keys.sort()

        expanded = {}

        for key in keys:
            value = namespace[key]
            flat_key = self._get_flat_key(key).lower()
            key_parts = flat_key.split('_')

            expanded_sub = expanded
            for i in range(0, len(key_parts)):
                key_part = key_parts[i]

                if i == len(key_parts) - 1:
                    expanded_sub[key_part] = value
                else:
                    if key_part not in expanded_sub:
                        expanded_sub[key_part] = {}
                    expanded_sub = expanded_sub[key_part]

        return expanded
