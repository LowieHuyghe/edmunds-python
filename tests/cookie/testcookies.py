
from tests.testcase import TestCase
from edmunds.cookie.cookies import Cookies
from flask import make_response


class TestCookies(TestCase):

    def test_cookies(self):
        """
        Test cookies
        :return:    void
        """

        rule = '/' + self.rand_str(20)
        given_cookies = {
            'key1': self.rand_str(20),
            'key2': self.rand_str(20),
            'key3': self.rand_str(20)
        }

        # Request context
        with self.app.test_request_context(rule):
            response = make_response()

            cookies = Cookies(given_cookies, response)

            # Check given values
            self.assert_equal(len(given_cookies), len(cookies))
            self.assert_equal(0, len(response.headers.get_all('Set-Cookie')))
            for key in given_cookies:
                self.assert_in(key, cookies)
                self.assert_equal(given_cookies[key], cookies[key])

    def test_set_cookies(self):
        """
        Test setting cookies
        :return:    void
        """

        rule = '/' + self.rand_str(20)
        given_cookies = {
            'key1': self.rand_str(20),
            'key2': self.rand_str(20),
            'key3': self.rand_str(20)
        }
        new_cookies = {
            'key3': self.rand_str(20),
            'key4': self.rand_str(20),
            'key5': self.rand_str(20)
        }

        # Request context
        with self.app.test_request_context(rule):
            response = make_response()

            cookies = Cookies(given_cookies, response)

            # Set new values
            for key in new_cookies:
                cookies[key] = new_cookies[key]

            # Check new cookies
            actual_cookies = given_cookies.copy()
            actual_cookies.update(new_cookies)

            self.assert_equal(len(new_cookies), len(response.headers.get_all('Set-Cookie')))

            for key in actual_cookies:
                self.assert_in(key, cookies)
                self.assert_equal(actual_cookies[key], cookies[key])

                found_in_cookies = False
                for cookie in response.headers.get_all('Set-Cookie'):
                    if key in cookie:
                        if found_in_cookies:
                            raise RuntimeError('Found %s multiple times in cookies' % key)
                        found_in_cookies = True
                        self.assert_in(actual_cookies[key], cookie)
                self.assert_equal(key in new_cookies, found_in_cookies)

    def test_delete_cookie(self):
        """
        Test delete cookie
        :return:    void
        """

        rule = '/' + self.rand_str(20)
        given_cookies = {
            'key1': self.rand_str(20),
            'key2': self.rand_str(20),
            'key3': self.rand_str(20)
        }
        delete_cookies = [
            'key1',
            'key3'
        ]

        # Request context
        with self.app.test_request_context(rule):
            response = make_response()

            cookies = Cookies(given_cookies, response)
            actual_cookies = given_cookies.copy()

            # Delete values
            for key in delete_cookies:
                cookies.pop(key, None)

            # Check new values
            self.assert_equal(len(delete_cookies), len(response.headers.get_all('Set-Cookie')))
            for key in delete_cookies:
                self.assert_not_in(key, cookies)

                found_in_cookies = False
                for cookie in response.headers.get_all('Set-Cookie'):
                    if key in cookie:
                        if found_in_cookies:
                            raise RuntimeError('Found %s multiple times in cookies' % key)
                        found_in_cookies = True
                        self.assert_not_in(actual_cookies[key], cookie)
                self.assert_equal(key in delete_cookies, found_in_cookies)

    def test_set_cookies_with_set(self):
        """
        Test setting cookies with set method
        :return:    void
        """

        rule = '/' + self.rand_str(20)
        given_cookies = {
            'key1': self.rand_str(20),
            'key2': self.rand_str(20),
            'key3': self.rand_str(20)
        }
        new_cookies = {
            'key3': self.rand_str(20),
            'key4': self.rand_str(20),
            'key5': self.rand_str(20)
        }

        # Request context
        with self.app.test_request_context(rule):
            response = make_response()

            cookies = Cookies(given_cookies, response)

            # Set new values
            for key in new_cookies:
                cookies.set(key, new_cookies[key])

            # Check new cookies
            actual_cookies = given_cookies.copy()
            actual_cookies.update(new_cookies)

            self.assert_equal(len(new_cookies), len(response.headers.get_all('Set-Cookie')))

            for key in actual_cookies:
                self.assert_in(key, cookies)
                self.assert_equal(actual_cookies[key], cookies[key])

                found_in_cookies = False
                for cookie in response.headers.get_all('Set-Cookie'):
                    if key in cookie:
                        if found_in_cookies:
                            raise RuntimeError('Found %s multiple times in cookies' % key)
                        found_in_cookies = True
                        self.assert_in(actual_cookies[key], cookie)
                self.assert_equal(key in new_cookies, found_in_cookies)

    def test_cookies_always_delete(self):
        """
        Test cookies always delete
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Request context
        with self.app.test_request_context(rule):
            response = make_response()

            cookies = Cookies({}, response)

            # Set value and check headers
            cookies['key1'] = 'value'
            self.assert_equal(1, len(response.headers.get_all('Set-Cookie')))

            # No headers should be set
            del cookies['key1']
            self.assert_equal(1, len(response.headers.get_all('Set-Cookie')))

    def test_cookies_single_set(self):
        """
        Test cookies only set single key
        :return:    void
        """

        rule = '/' + self.rand_str(20)

        # Request context
        with self.app.test_request_context(rule):
            response = make_response()

            cookies = Cookies({}, response)

            # Set value and check headers
            cookies['key1'] = 'value1'
            self.assert_equal(1, len(response.headers.get_all('Set-Cookie')))

            # Set value a second time
            cookies['key1'] = 'value2'
            self.assert_equal(1, len(response.headers.get_all('Set-Cookie')))
