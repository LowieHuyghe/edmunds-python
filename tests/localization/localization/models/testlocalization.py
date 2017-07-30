
from tests.testcase import TestCase
from edmunds.localization.localization.models.time import Time
from edmunds.localization.localization.models.number import Number
from edmunds.localization.localization.models.localization import Localization
from babel.core import Locale
from babel.dates import get_timezone


class TestLocalization(TestCase):
    """
    Test Localization model
    """

    def test_localization(self):
        """
        Test localization
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        time_zone = get_timezone('Europe/Brussels')
        time_obj = Time(locale=locale, time_zone=time_zone)
        number_obj = Number(locale=locale)
        localization_obj = Localization(locale=locale, number=number_obj, time=time_obj)

        self.assert_is_instance(localization_obj.locale, Locale)
        self.assert_is_instance(localization_obj.number, Number)
        self.assert_is_instance(localization_obj.time, Time)
        self.assert_is_instance(localization_obj.rtl, bool)

    def test_rtl(self):
        """
        Test rtl
        :return:    void
        """

        locale = Locale.parse('nl_BE', sep='_')
        time_zone = get_timezone('Europe/Brussels')
        time_obj = Time(locale=locale, time_zone=time_zone)
        number_obj = Number(locale=locale)
        localization_obj = Localization(locale=locale, number=number_obj, time=time_obj)
        self.assert_false(localization_obj.rtl)

        locale = Locale.parse('ar_DZ', sep='_')
        time_zone = get_timezone('Europe/Brussels')
        time_obj = Time(locale=locale, time_zone=time_zone)
        number_obj = Number(locale=locale)
        localization_obj = Localization(locale=locale, number=number_obj, time=time_obj)
        self.assert_true(localization_obj.rtl)
