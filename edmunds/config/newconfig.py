
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

        expanded = None

        for key in keys:
            value = namespace[key]
            flat_key = self._get_flat_key(key).lower()
            key_parts = flat_key.split('_')

            expanded = self._get_expanded_namespace_loop(key_parts, value, expanded)

        return expanded

    def _get_expanded_namespace_loop(self, keys, value, expanded=None):
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
            set_value = self._get_expanded_namespace_loop(next_keys, value, expanded[key])
        else:
            set_value = self._get_expanded_namespace_loop(next_keys, value)

        if isinstance(expanded, list):
            expanded.append(set_value)
        else:
            expanded[key] = set_value

        return expanded
