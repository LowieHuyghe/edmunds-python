
from tests.testcase import TestCase
from edmunds.http.input import Input
from edmunds.globals import request
from edmunds.encoding.encoding import Encoding
from werkzeug.datastructures import FileStorage
from edmunds.validation.validator import Validator
from wtforms import Form, BooleanField, StringField, PasswordField, validators
import sys
if sys.version_info < (3, 0):
    from cStringIO import StringIO
else:
    from io import StringIO
    from io import BytesIO


class TestInput(TestCase):
    """
    Test Input
    """

    def test_no_input(self):
        """
        Test no input
        :return: void
        """

        rule = '/' + self.rand_str(20)

        # Call route
        with self.app.test_request_context(rule):
            input = Input(request)

            self.assert_equal(0, len(input))

    def test_get_input(self):
        """
        Test get input
        :return: void
        """

        get_arguments = [
            ('get_argument_string_1', ''),
            ('get_argument_string_2', self.rand_str(5)),
            ('get_argument_string_3', self.rand_str(10)),
            ('get_argument_integer_1', 0),
            ('get_argument_integer_2', self.rand_int(1, 10)),
            ('get_argument_integer_3', self.rand_int(20, 100)),
        ]
        rule = self._get_url_with_arguments('/' + self.rand_str(20), get_arguments)

        # Call route
        with self.app.test_request_context(rule):
            input = Input(request)

            self.assert_equal(len(get_arguments), len(input))
            for key, value in get_arguments:
                self.assert_in(key, input)
                self.assert_equal(str(value), Encoding.normalize(input[key]))

    def test_post_input(self):
        """
        Test post input
        :return: void
        """

        post_arguments = [
            ('post_argument_string_1', ''),
            ('post_argument_string_2', self.rand_str(5)),
            ('post_argument_string_3', self.rand_str(10)),
            ('post_argument_integer_1', 0),
            ('post_argument_integer_2', self.rand_int(1, 10)),
            ('post_argument_integer_3', self.rand_int(20, 100)),
        ]
        rule = '/' + self.rand_str(20)

        # Make post data
        data = dict()
        for key, value in post_arguments:
            data[key] = value

        # Call route
        with self.app.test_request_context(rule, method='POST', data=data):
            input = Input(request)

            self.assert_equal(len(post_arguments), len(input))
            for key, value in post_arguments:
                self.assert_in(key, input)
                self.assert_equal(str(value), Encoding.normalize(input[key]))

    def test_file_input(self):
        """
        Test file input
        :return: void
        """

        file_arguments = [
            ('file_argument_1', Encoding.normalize('')),
            ('file_argument_2', self.rand_str(5)),
            ('file_argument_3', self.rand_str(10)),
        ]
        rule = '/' + self.rand_str(20)

        # Make post data
        data = dict()
        for key, value in file_arguments:
            if sys.version_info < (3, 0):
                value = StringIO(value)
            else:
                value = BytesIO(value.encode())
            data[key] = (value, key + '.txt')

        # Call route
        with self.app.test_request_context(rule, method='POST', data=data):
            input = Input(request)

            self.assert_equal(len(file_arguments), len(input))
            for key, value in file_arguments:
                self.assert_in(key, input)
                self.assert_is_instance(input[key], FileStorage)
                self.assert_equal(key + '.txt', Encoding.normalize(input[key].filename))
                self.assert_equal(value, Encoding.normalize(input[key].stream.read()))

    def test_combined_input(self):
        """
        Test combined input
        :return: void
        """

        get_arguments = [
            ('get_argument_string_1', ''),
            ('get_argument_string_2', self.rand_str(5)),
            ('get_argument_string_3', self.rand_str(10)),
            ('get_argument_integer_1', 0),
            ('get_argument_integer_2', self.rand_int(1, 10)),
            ('get_argument_integer_3', self.rand_int(20, 100)),
        ]
        post_arguments = [
            ('post_argument_string_1', ''),
            ('post_argument_string_2', self.rand_str(5)),
            ('post_argument_string_3', self.rand_str(10)),
            ('post_argument_integer_1', 0),
            ('post_argument_integer_2', self.rand_int(1, 10)),
            ('post_argument_integer_3', self.rand_int(20, 100)),
        ]
        file_arguments = [
            ('file_argument_1', ''),
            ('file_argument_2', self.rand_str(5)),
            ('file_argument_3', self.rand_str(10)),
        ]
        rule = self._get_url_with_arguments('/' + self.rand_str(20), get_arguments)

        # Make post data
        data = dict()
        for key, value in post_arguments:
            data[key] = value
        for key, value in file_arguments:
            if sys.version_info < (3, 0):
                value = StringIO(value)
            else:
                value = BytesIO(value.encode())
            data[key] = (value, key + '.txt')

        # Call route
        with self.app.test_request_context(rule, method='POST', data=data):
            input = Input(request)

            self.assert_equal(len(get_arguments) + len(post_arguments) + len(file_arguments), len(input))
            for key, value in get_arguments:
                self.assert_in(key, input)
                self.assert_equal(str(value), Encoding.normalize(input[key]))
            for key, value in post_arguments:
                self.assert_in(key, input)
                self.assert_equal(str(value), Encoding.normalize(input[key]))
            for key, value in file_arguments:
                self.assert_in(key, input)
                self.assert_is_instance(input[key], FileStorage)
                self.assert_equal(key + '.txt', Encoding.normalize(input[key].filename))
                self.assert_equal(value, Encoding.normalize(input[key].stream.read()))

    def test_validate(self):
        """
        Test validation
        :return:    void
        """

        data = [
            (False, [], []),
            (False, [('email', 'test@example.com'), ('password', 'pass')], [('confirm', ''), ('accept_tos', True)]),
            (False, [('email', 'test@example.com')], [('password', 'pass'), ('confirm', 'pass')]),
            (False, [('email', 'test@example.com'), ('password', 'pass'), ('confirm', 'passs')], [('accept_tos', True)]),
            (True, [('email', 'test@example.com'), ('password', 'pass')], [('confirm', 'pass'), ('accept_tos', True)]),
            (True, [('email', 'test@example.com'), ('password', 'pass'), ('confirm', 'pass'), ('accept_tos', True)], []),
            (True, [], [('email', 'test@example.com'), ('password', 'pass'), ('confirm', 'pass'), ('accept_tos', True)]),
        ]

        for expected, get_arguments, post_arguments in data:
            rule = self._get_url_with_arguments('/' + self.rand_str(20), get_arguments)

            # Make post data
            post_data = dict()
            for key, value in post_arguments:
                post_data[key] = value

            # Call route
            with self.app.test_request_context(rule, method='POST', data=post_data):
                input = Input(request)
                self.assert_equal(len(get_arguments) + len(post_arguments), len(input))

                validator = input.validate(MyValidator)

                self.assert_is_instance(validator, Form)
                self.assert_is_not_none(validator.validates)
                self.assert_equal(expected, validator.validates)

    def _get_url_with_arguments(self, url, arguments):
        """
        Get url with arguments
        :param url:         Url
        :param arguments:   Arguments
        :return:            Url
        """

        first = True
        for key, value in arguments:
            if first:
                first = False
                url += '?'
            else:
                url += '&'
            url += key + '=' + str(value)

        return url


class MyValidator(Validator):
    email = StringField('Email Address', [validators.Length(min=6, max=35)])
    password = PasswordField('New Password', [
        validators.DataRequired(),
        validators.EqualTo('confirm', message='Passwords must match')
    ])
    confirm = PasswordField('Repeat Password')
    accept_tos = BooleanField('I accept the TOS', [validators.DataRequired()])
