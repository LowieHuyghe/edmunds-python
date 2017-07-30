
from babel.dates import format_date, format_datetime, format_time, format_interval, LC_TIME


class Time(object):

    FULL = 'full'
    LONG = 'long'
    MEDIUM = 'medium'
    SHORT = 'short'

    def __init__(self, locale=None, time_zone=None):
        """
        Constructor
        :param locale:      The locale to use
        :type locale:       babel.core.Locale
        :param time_zone:   The time zone
        :type time_zone:    pytz.tzinfo.DstTzInfo
        """

        if locale is not None:
            self._locale = locale
        else:
            self._locale = LC_TIME
        self.time_zone = time_zone

    def date(self, date, format=MEDIUM):
        """
        Format date
        :param date:    The date 
        :param format:  The format 
        :return:        Formatted date
        """
        return format_date(date, format=format, locale=self._locale)

    def datetime(self, datetime, format=MEDIUM):
        """
        Format datetime
        :param datetime:    The datetime 
        :param format:  The format 
        :return:        Formatted datetime
        """
        return format_datetime(datetime, format=format, locale=self._locale, tzinfo=self.time_zone)

    def time(self, time, format=MEDIUM):
        """
        Format time
        :param time:    The time 
        :param format:  The format 
        :return:        Formatted time
        """
        return format_time(time, format=format, locale=self._locale, tzinfo=self.time_zone)

    def interval(self, start_datetime, end_datetime, skeleton=None):
        """
        Format interval
        :param start_datetime:  Start time/date/datetime 
        :param end_datetime:    End time/date/datetime
        :param skeleton:        The "skeleton format" to use for formatting
        :return:                The formatted interval
        """
        return format_interval(start_datetime, end_datetime, skeleton=skeleton, locale=self._locale, tzinfo=self.time_zone)
