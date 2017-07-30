
from tests.testcase import TestCase
from edmunds.localization.localization.models.number import Number
from babel.core import Locale


class TestNumber(TestCase):
    """
    Test Number model
    """

    def test_currency(self):
        """
        Test currency
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        number_obj = Number(locale=locale)

        data = [
            (u'34,00\xa0\u20ac', 34, 'EUR'),
            (u'0,34\xa0\u20ac', 0.34, 'EUR'),
            (u'9,23\xa0\u20ac', 9.23, 'EUR'),
            (u'232.339,00\xa0\u20ac', 232339, 'EUR'),
        ]
        for expected, given_value, given_currency_code in data:
            self.assert_equal(expected, number_obj.currency(given_value, given_currency_code))

    def test_number(self):
        """
        Test number
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        number_obj = Number(locale=locale)

        data = [
            ('34', 34),
            ('0,34', 0.34),
            ('9,23', 9.23),
            ('232.339', 232339),
        ]
        for expected, given_value in data:
            self.assert_equal(expected, number_obj.number(given_value))

    def test_percent(self):
        """
        Test percent
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        number_obj = Number(locale=locale)

        data = [
            ('34%', 0.34),
            ('0%', 0.0034),
            ('9%', 0.0923),
            ('232.339%', 2323.39),
        ]
        for expected, given_value in data:
            self.assert_equal(expected, number_obj.percent(given_value))

    def test_scientific(self):
        """
        Test scientific
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        number_obj = Number(locale=locale)

        data = [
            ('3E1', 34),
            ('3E-1', 0.34),
            ('9E0', 9.23),
            ('2E5', 232339),
        ]
        for expected, given_value in data:
            self.assert_equal(expected, number_obj.scientific(given_value))
