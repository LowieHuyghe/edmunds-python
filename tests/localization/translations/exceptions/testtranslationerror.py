
from tests.testcase import TestCase
from edmunds.localization.translations.exceptions.translationerror import TranslationError


class TestTranslationError(TestCase):
    """
    Test the Translation Error
    """

    def test_error(self):
        """
        Test error
        :return:    void
        """

        error = TranslationError()
        self.assert_is_instance(error, RuntimeError)
