
from tests.testcase import TestCase
from edmunds.http.response import Response
from flask.wrappers import Response as FlaskResponse
from edmunds.http.responsehelper import ResponseHelper
from jinja2 import Template
import json
from edmunds.encoding.encoding import Encoding
from werkzeug.wsgi import FileWrapper
import sys
import io
import os.path


class TestResponseHelper(TestCase):
    """
    Test ResponseHelper
    """

    def set_up(self):
        """
        Set up
        :return:    void
        """
        super(TestResponseHelper, self).set_up()

        self.template_source = '{{ value1 }} {{ value2 }} {{ value3 }}'
        self.template = Template(self.template_source)
        self.template_file = self.write_temp_file(self.template_source)

    def test_status(self):
        """
        Test status
        :return:    void
        """

        helper = ResponseHelper()
        rule = '/' + self.rand_str(20)
        default = 200
        default_redirect = 302
        status = 105

        with self.app.test_request_context(rule):
            # None
            self.assert_is_none(helper._status)

            # Check responses
            self.assert_equal(default, helper.raw('').status_code)
            self.assert_equal(default, helper.render(self.template).status_code)
            self.assert_equal(default, helper.json().status_code)
            self.assert_equal(default_redirect, helper.redirect('/').status_code)
            file_response = helper.file(os.path.dirname(self.template_file), os.path.basename(self.template_file))
            self.assert_equal(default, file_response.status_code)
            file_response.close()

            # Set & check
            helper.status(status)
            self.assert_equal(status, helper._status)

            # Check responses
            self.assert_equal(status, helper.raw('').status_code)
            self.assert_equal(status, helper.render(self.template).status_code)
            self.assert_equal(status, helper.json().status_code)
            self.assert_equal(status, helper.redirect('/').status_code)
            file_response = helper.file(os.path.dirname(self.template_file), os.path.basename(self.template_file))
            self.assert_equal(status, file_response.status_code)
            file_response.close()

    def test_assigns(self):
        """
        Test assigns
        :return:    void
        """

        helper = ResponseHelper()
        key1 = self.rand_str(20)
        value1 = self.rand_str(20)
        value1_2 = self.rand_str(20)
        key2 = self.rand_str(20)
        value2 = self.rand_str(20)

        # Empty
        self.assert_equal(0, len(helper.assigns))

        # Assign one
        helper.assign(key1, value1)
        self.assert_equal(1, len(helper.assigns))
        self.assert_in(key1, helper.assigns)
        self.assert_equal(value1, helper.assigns[key1])

        # Assign second
        helper.assign(key2, value2)
        self.assert_equal(2, len(helper.assigns))
        self.assert_in(key2, helper.assigns)
        self.assert_equal(value2, helper.assigns[key2])

        # Override
        helper.assign(key1, value1_2)
        self.assert_equal(2, len(helper.assigns))
        self.assert_in(key1, helper.assigns)
        self.assert_equal(value1_2, helper.assigns[key1])

    def test_headers(self):
        """
        Test headers
        :return:    void
        """

        helper = ResponseHelper()
        rule = '/' + self.rand_str(20)
        key1 = self.rand_str(20)
        value1 = self.rand_str(20)
        value1_2 = self.rand_str(20)
        key2 = self.rand_str(20)
        value2 = self.rand_str(20)

        # Empty
        self.assert_equal(0, len(helper.headers))

        # Assign one
        helper.header(key1, value1)
        self.assert_equal(1, len(helper.headers))
        self.assert_in(key1, helper.headers)
        self.assert_equal(value1, helper.headers[key1])

        # Assign second
        helper.header(key2, value2)
        self.assert_equal(2, len(helper.headers))
        self.assert_in(key2, helper.headers)
        self.assert_equal(value2, helper.headers[key2])

        # Override
        helper.header(key1, value1_2)
        self.assert_equal(2, len(helper.headers))
        self.assert_in(key1, helper.headers)
        self.assert_equal(value1_2, helper.headers[key1])

        # Check responses
        with self.app.test_request_context(rule):
            responses = [
                helper.raw(''),
                helper.render(self.template),
                helper.json(),
                helper.redirect('/'),
                helper.file(os.path.dirname(self.template_file), os.path.basename(self.template_file))
            ]
            for response in responses:
                self.assert_in(key1, response.headers)
                self.assert_equal(value1_2, response.headers[key1])
                self.assert_in(key2, response.headers)
                self.assert_equal(value2, response.headers[key2])
            responses[4].close()

    def test_cookies(self):
        """
        Test cookies
        :return:    void
        """

        helper = ResponseHelper()
        rule = '/' + self.rand_str(20)
        key1 = self.rand_str(20)
        value1 = self.rand_str(20)
        value1_2 = self.rand_str(20)
        key2 = self.rand_str(20)
        value2 = self.rand_str(20)

        with self.app.test_request_context(rule):
            # Empty
            self.assert_equal(0, len(helper._cookie_response.headers))

            # Assign one
            helper.cookie(key1, value1)
            self.assert_equal(1, len(helper._cookie_response.headers))
            self.assert_equal('Set-Cookie', helper._cookie_response.headers[0][0])
            self.assert_in('%s=%s;' % (key1, value1), helper._cookie_response.headers[0][1])

            # Assign second
            helper.cookie(key2, value2)
            self.assert_equal(2, len(helper._cookie_response.headers))
            self.assert_equal('Set-Cookie', helper._cookie_response.headers[1][0])
            self.assert_in('%s=%s;' % (key2, value2), helper._cookie_response.headers[1][1])

            # Override (NOT)
            helper.cookie(key1, value1_2)
            self.assert_equal(3, len(helper._cookie_response.headers))
            self.assert_equal('Set-Cookie', helper._cookie_response.headers[2][0])
            self.assert_in('%s=%s;' % (key1, value1_2), helper._cookie_response.headers[2][1])

            # Delete
            helper.delete_cookie(key2)
            self.assert_equal(4, len(helper._cookie_response.headers))
            self.assert_equal('Set-Cookie', helper._cookie_response.headers[3][0])
            self.assert_in('%s=;' % key2, helper._cookie_response.headers[3][1])

            # Check responses
            responses = [
                helper.raw(''),
                helper.render(self.template),
                helper.json(),
                helper.redirect('/'),
                helper.file(os.path.dirname(self.template_file), os.path.basename(self.template_file))
            ]
            for response in responses:
                cookie_headers = list(filter(lambda header: isinstance(header, tuple) and header[0] == 'Set-Cookie', response.headers))

                self.assert_equal(4, len(cookie_headers))
                self.assert_in('%s=%s;' % (key1, value1), cookie_headers[0][1])
                self.assert_in('%s=%s;' % (key2, value2), cookie_headers[1][1])
                self.assert_in('%s=%s;' % (key1, value1_2), cookie_headers[2][1])
                self.assert_in('%s=;' % key2, cookie_headers[3][1])
            responses[4].close()

    def test_raw(self):
        """
        Test raw
        :return:    void
        """

        helper = ResponseHelper()
        rule = '/' + self.rand_str(20)

        with self.app.test_request_context(rule):
            response = helper.raw(self.template_source)

            self.assert_is_instance(response, Response)
            self.assert_is_instance(response, FlaskResponse)
            self.assert_equal(1, len(response.response))
            self.assert_equal(self.template_source, Encoding.normalize(response.response[0]))

    def test_render(self):
        """
        Test render
        :return:    void
        """

        helper = ResponseHelper()
        rule = '/' + self.rand_str(20)
        value1 = self.rand_str(20)
        value2 = self.rand_str(20)
        value3 = self.rand_str(20)

        incomplete_result = '%s  %s' % (value1, value3)
        alternate_result = '%s %s %s' % (value1, value2, value3)

        with self.app.test_request_context(rule):
            # Assign normal way
            helper.assign('value1', value1)
            helper.assign('value3', value3)

            # Fetch response
            response = helper.render(self.template)
            # Check
            self.assert_is_instance(response, Response)
            self.assert_is_instance(response, FlaskResponse)
            self.assert_equal(1, len(response.response))
            self.assert_equal(incomplete_result, Encoding.normalize(response.response[0]))

            # Fetch response with extra assign
            response = helper.render(self.template, {'value2': value2})
            # Check
            self.assert_is_instance(response, Response)
            self.assert_is_instance(response, FlaskResponse)
            self.assert_equal(1, len(response.response))
            self.assert_equal(alternate_result, Encoding.normalize(response.response[0]))

    def test_render_template(self):
        """
        Test render
        :return:    void
        """

        helper = ResponseHelper()
        rule = '/' + self.rand_str(20)
        value1 = self.rand_str(20)
        value2 = self.rand_str(20)
        value3 = self.rand_str(20)

        incomplete_result = '%s  %s' % (value1, value3)
        alternate_result = '%s %s %s' % (value1, value2, value3)

        with self.app.test_request_context(rule):
            # Assign normal way
            helper.assign('value1', value1)
            helper.assign('value3', value3)

            # Fetch rendered template
            self.assert_equal(incomplete_result, helper.render_template(self.template))

            # Fetch rendered template
            self.assert_equal(alternate_result, helper.render_template(self.template, {'value2': value2}))

    def test_json(self):
        """
        Test json response
        :return:    void
        """

        helper = ResponseHelper()
        rule = '/' + self.rand_str(20)
        key1 = self.rand_str(20)
        value1 = self.rand_str(20)
        key2 = self.rand_str(20)
        value2 = self.rand_str(20)

        with self.app.test_request_context(rule):
            # Empty json response
            response = helper.json()
            # Check
            self.assert_is_instance(response, Response)
            self.assert_is_instance(response, FlaskResponse)
            self.assert_equal('{}\n', Encoding.normalize(response.response[0]))

            # Assign
            helper.assign(key1, value1)
            helper.assign(key2, value2)
            # Json response
            response = helper.json()
            # Check
            self.assert_is_instance(response, Response)
            self.assert_is_instance(response, FlaskResponse)
            response_json = Encoding.normalize(json.loads(response.response[0]))
            self.assert_equal(2, len(response_json))
            self.assert_in(key1, response_json)
            self.assert_equal(value1, response_json[key1])
            self.assert_in(key2, response_json)
            self.assert_equal(value2, response_json[key2])

    def test_redirect(self):
        """
        Test redirect
        :return:    void
        """

        helper = ResponseHelper()
        rule = '/' + self.rand_str(20)

        with self.app.test_request_context(rule):
            # Redirect response
            response = helper.redirect(rule)
            # Check
            self.assert_is_instance(response, Response)
            self.assert_is_instance(response, FlaskResponse)
            self.assert_in(rule, Encoding.normalize(response.response[0]))

    def test_file(self):
        """
        Test file
        :return:    void
        """

        helper = ResponseHelper()
        rule = '/' + self.rand_str(20)

        with self.app.test_request_context(rule):
            # Redirect response
            response = helper.file(os.path.dirname(self.template_file), os.path.basename(self.template_file))
            # Check
            self.assert_is_instance(response, Response)
            self.assert_is_instance(response, FlaskResponse)
            self.assert_is_instance(response.response, FileWrapper)
            if sys.version_info < (3, 0):
                self.assert_is_instance(response.response.file, file)
                self.assert_in(self.template_source, response.response.file)
            else:
                self.assert_is_instance(response.response.file, io.BufferedReader)
                self.assert_in(self.template_source, Encoding.normalize(response.response.file.read()))
            self.assert_equal(self.template_file, response.response.file.name)

            response.close()
