
import sys


class Encoding(object):

    @staticmethod
    def normalize(value):
        """
        Normalize value
        :param value:   The value
        :return:        The processed value
        """

        # Python 2 vs Python 3
        if sys.version_info < (3, 0):
            return Encoding.to_ascii(value)
        else:
            return Encoding.to_unicode(value)

    @staticmethod
    def to_ascii(value):
        """
        To ascii
        :param value:   The value
        :return:        The processed value
        """

        # Dict
        if isinstance(value, dict):
            processed_value = {}
            for key in value:
                if Encoding._is_unicode(key):
                    processed_key = key.encode('ascii')
                else:
                    processed_key = key
                processed_value[processed_key] = Encoding.to_ascii(value[key])

        # List
        elif isinstance(value, list):
            processed_value = []
            for value in value:
                processed_value.append(Encoding.to_ascii(value))

        # Unicode
        elif Encoding._is_unicode(value):
            processed_value = value.encode('ascii')

        else:
            processed_value = value

        return processed_value

    @staticmethod
    def to_unicode(value):
        """
        To unicode
        :param value:   The value
        :return:        The processed value
        """

        # Dict
        if isinstance(value, dict):
            processed_value = {}
            for key in value:
                if Encoding._is_ascii(key):
                    processed_key = key.decode('utf-8')
                else:
                    processed_key = key
                processed_value[processed_key] = Encoding.to_unicode(value[key])

        # List
        elif isinstance(value, list):
            processed_value = []
            for value in value:
                processed_value.append(Encoding.to_unicode(value))

        # Unicode
        elif Encoding._is_ascii(value):
            processed_value = value.decode('utf-8')

        else:
            processed_value = value

        return processed_value

    @staticmethod
    def get_text_type():
        """
        Get text type
        :return:    class
        """
        if sys.version_info < (3, 0):
            return unicode
        else:
            return str

    @staticmethod
    def _is_ascii(value):
        """
        Check if ascii
        :param value:   The value
        :return:        Ascii or not
        """

        # Python 2 vs Python 3
        if sys.version_info < (3, 0):
            return isinstance(value, str)
        else:
            return isinstance(value, bytes)

    @staticmethod
    def _is_unicode(value):
        """
        Check if unicode
        :param value:   The value
        :return:        Ascii or not
        """

        # Python 2 vs Python 3
        if sys.version_info < (3, 0):
            return isinstance(value, unicode)
        else:
            return isinstance(value, str)
