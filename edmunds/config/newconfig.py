
from flask.config import Config as FlaskConfig


class Config(FlaskConfig):
    """
    Config module
    """

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
            self._update(mixed)
        else:
            return self._get(mixed, default=default)

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

    def _update(self, update):
        """
        Update values
        :param update:  The update
        :type update:   dict
        :return:        void
        """
        for key in update:
            flat_key = self._get_flat_key(key)

            for i in list(self.keys()):
                if i.startswith(flat_key):
                    del self[i]

            self[flat_key] = update[key]

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
