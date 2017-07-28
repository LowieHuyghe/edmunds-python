
from tests.testcase import TestCase
from edmunds.http.response import Response
from flask.wrappers import Response as FlaskResponse
from edmunds.http.responsehelper import ResponseHelper


class TestResponseHelper(TestCase):
    """
    Test ResponseHelper
    """

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
