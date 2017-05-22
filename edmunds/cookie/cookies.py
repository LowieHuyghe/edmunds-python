
from collections import MutableMapping
import re


class Cookies(MutableMapping):

    def __init__(self, cookies, response):
        """
        Construct cookies
        :param cookies:     The given cookies
        :param response:    The response
        """

        self._dict = dict(cookies)
        self._given_keys = list(cookies.keys())
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

        self._delete_cookie_header_if_set(k)
        self._response.set_cookie(k, v)

        self._dict[k] = v

    def __getitem__(self, k):
        """
        Get item
        :param k: key
        :return:    mixed
        """

        return self._dict[k]

    def __delitem__(self, k):
        """
        Delete key
        :param k:   Key
        :return:    void
        """

        self._delete_cookie_header_if_set(k)
        self._response.set_cookie(k, '', expires=0)

        del self._dict[k]

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

    def _delete_cookie_header_if_set(self, k):
        """
        Delete cookie header if set
        :param k:   Key
        :return:    Deleted key
        """

        key_already_set = False
        set_cookies = self._response.headers.get_all('Set-Cookie')
        regex_pattern = r'(^|\W)' + k + r'\s*=.*$'

        for set_cookie in set_cookies:
            if re.match(regex_pattern, set_cookie):
                key_already_set = True
                break
        if key_already_set:
            self._response.headers.remove('Set-Cookie')
            for re_set_cookie in set_cookies:
                if not re.match(regex_pattern, re_set_cookie):
                    self._response.headers.add('Set-Cookie', re_set_cookie)

        return key_already_set
