
from tests.testcase import TestCase
from edmunds.http.response import Response
from flask.wrappers import Response as FlaskResponse
from edmunds.http.responsehelper import ResponseHelper
from jinja2 import Template


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

        self.template_source = '{{ value1 }}' \
                               '{{ value2 }}' \
                               '{{ value3 }}'
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
            self.assert_equal(default, helper.file(self.template_file).status_code)

            # Set & check
            helper.status(status)
            self.assert_equal(status, helper._status)

            # Check responses
            self.assert_equal(status, helper.raw('').status_code)
            self.assert_equal(status, helper.render(self.template).status_code)
            self.assert_equal(status, helper.json().status_code)
            self.assert_equal(status, helper.redirect('/').status_code)
            self.assert_equal(status, helper.file(self.template_file).status_code)

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
