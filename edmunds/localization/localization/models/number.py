
from babel.numbers import format_currency, format_decimal, format_number, format_percent, format_scientific


class Number(object):

    def __init__(self, locale):
        """
        Constructor
        :param locale:  The locale to use
        :type locale:   babel.core.Locale
        """

        self._locale = locale

    def currency(self, value, currency_code):
        """
        Format currency
        :param value:           The value
        :param currency_code:   The currency-code
        :return:                Formatted value
        """
        return format_currency(value, currency=currency_code, locale=self._locale)

    def number(self, value):
        """
        Format number
        :param value:   The value to format
        :return:        The formatted number
        """
        if value % 1 == 0:
            return format_number(value, locale=self._locale)
        else:
            return format_decimal(value, locale=self._locale)

    def percent(self, value):
        """
        Format percent
        :param value:   The value to format
        :return:        The formatted value
        """
        return format_percent(value, locale=self._locale)

    def scientific(self, value, format=None):
        """
        Format scientific value
        :param value:   The value to format
        :param format:  The format
        :return:        The formatted value
        """
        return format_scientific(value, format=format, locale=self._locale)
