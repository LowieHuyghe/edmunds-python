
from tests.testcase import TestCase
from edmunds.localization.localization.models.time import Time
from babel.core import Locale
from babel.dates import get_timezone
from datetime import date, datetime, time
from edmunds.encoding.encoding import Encoding


class TestTime(TestCase):
    """
    Test Time model
    """

    def test_time(self):
        """
        Test time
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        time_zone = get_timezone('Europe/Brussels')
        time_obj = Time(locale=locale, time_zone=time_zone)

        data = [
            ('05:26', time(5, 26, 13), Time.SHORT),
            ('05:06:07', time(5, 6, 7), Time.MEDIUM),
            ('14:26:57 CET', time(14, 26, 57), Time.LONG),
            ('01:02:03 Midden-Europese standaardtijd', time(1, 2, 3), Time.FULL),
        ]
        for expected, given_time, given_format in data:
            self.assert_equal(expected, Encoding.normalize(time_obj.time(given_time, given_format)))

    def test_datetime(self):
        """
        Test datetime
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        time_zone = get_timezone('Europe/Brussels')
        time_obj = Time(locale=locale, time_zone=time_zone)

        data = [
            ('9/05/92 07:26', datetime(1992, 5, 9, 5, 26, 13), Time.SHORT),
            ('3 apr. 2004 07:06:07', datetime(2004, 4, 3, 5, 6, 7), Time.MEDIUM),
            ('18 februari 2016 15:26:57 CET', datetime(2016, 2, 18, 14, 26, 57), Time.LONG),
            ('zondag 30 juli 2017 03:02:03 Midden-Europese zomertijd', datetime(2017, 7, 30, 1, 2, 3), Time.FULL),
        ]
        for expected, given_datetime, given_format in data:
            self.assert_equal(expected, Encoding.normalize(time_obj.datetime(given_datetime, given_format)))

    def test_date(self):
        """
        Test date
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        time_zone = get_timezone('Europe/Brussels')
        time_obj = Time(locale=locale, time_zone=time_zone)

        data = [
            ('9/05/92', date(1992, 5, 9), Time.SHORT),
            ('3 apr. 2004', date(2004, 4, 3), Time.MEDIUM),
            ('18 februari 2016', date(2016, 2, 18), Time.LONG),
            ('zondag 30 juli 2017', date(2017, 7, 30), Time.FULL),
        ]
        for expected, given_date, given_format in data:
            self.assert_equal(expected, Encoding.normalize(time_obj.date(given_date, given_format)))

    def test_interval(self):
        """
        Test interval
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        time_zone = get_timezone('Europe/Brussels')
        time_obj = Time(locale=locale, time_zone=time_zone)

        data = [
            ('09:05:23 - 14:05:06', time(9, 5, 23), time(14, 5, 6)),
            ('3 apr. 2004 07:06:07 - 30 jul. 2017 03:02:03', datetime(2004, 4, 3, 5, 6, 7), datetime(2017, 7, 30, 1, 2, 3)),
            ('18 feb. 2016 15:26:57 - 30 jul. 2017 02:00:00', datetime(2016, 2, 18, 14, 26, 57), date(2017, 7, 30)),
            ('9 mei 1992 - 30 jul. 2017', date(1992, 5, 9), date(2017, 7, 30)),
        ]
        for expected, given_start, given_end in data:
            self.assert_equal(expected, Encoding.normalize(time_obj.interval(given_start, given_end)))
