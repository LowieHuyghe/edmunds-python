
class Localization(object):

    def __init__(self, locale, number, time):
        """
        Constructor
        :param locale:  The locale
        :type locale:   babel.core.Locale
        :param number:  Number
        :type number:   edmunds.localization.localization.models.number.Number
        :param time:    Time
        :type time:     edmunds.localization.localization.models.time.Time    
        """

        self.locale = locale
        self.number = number
        self.time = time

    @property
    def rtl(self):
        """
        Check if rtl
        :return:    bool
        """
        return self.locale.character_order == 'right-to-left'
