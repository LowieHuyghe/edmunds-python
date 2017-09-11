
from tests.testcase import TestCase
from edmunds.localization.translations.exceptions.sentencefillererror import SentenceFillerError


class TestSentenceFillerError(TestCase):
    """
    Test the Sentence Filler Error
    """

    def test_error(self):
        """
        Test error
        :return:    void
        """

        error = SentenceFillerError()
        self.assert_is_instance(error, RuntimeError)
