
from collections import MutableMapping


class Cookies(MutableMapping):

    def __init__(self, cookies, response):
        """
        Construct cookies
        :param cookies:     The given cookies
        :param response:    The response
        """

        self._dict = dict(cookies)
        self._response = response

    def set(self, key, value='', expires=None, **kwargs):
        """
        Sets a cookie. The parameters are the same as in the cookie `Morsel`
        object in the Python standard library but it accepts unicode data, too.

        :param key: the key (name) of the cookie to be set.
        :param value: the value of the cookie.
        :param expires: should be a `datetime` object or UNIX timestamp.
        """

        self.__setitem__(key, value)

    def __setitem__(self, k, v):
        """
        Set item
        :param k:   Key 
        :param v:   Value
        :return:    void
        """

        self._response.set_cookie(k, v)

        self._dict[k] = v

    def __getitem__(self, k):
        """
        Get item
        :param k: key
        :return:    mixed
        """

        return self._dict[k]

    def __delitem__(self, v):
        """
        Delete key
        :param v:   Key
        :return:    void
        """

        self._response.set_cookie(v, '', expires=0)

        del self._dict[v]

    def __iter__(self):
        """
        Return iteratable
        :return:    iter
        """

        return iter(self._dict)

    def __len__(self):
        """
        Get length
        :return:    int
        """
        return len(self._dict)
