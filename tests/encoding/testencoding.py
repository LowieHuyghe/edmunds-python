
from scriptcore.testing.testcase import TestCase
from scriptcore.encoding.encoding import Encoding
import sys


class TestEncoding(TestCase):

    def test_normalize(self):
        """
        Test normalize function
        :return:    void
        """

        value = {
            u'key1': 'value1'.encode('ascii'),
            'key2': u'value2',
            'key3'.encode('ascii'): [
                'listvalue1',
                'listvalue2'.encode('ascii'),
                u'listvalue3',
            ]
        }
        if sys.version_info < (3, 0):
            expected = {
                'key1'.encode('ascii'): 'value1'.encode('ascii'),
                'key2'.encode('ascii'): 'value2'.encode('ascii'),
                'key3'.encode('ascii'): [
                    'listvalue1'.encode('ascii'),
                    'listvalue2'.encode('ascii'),
                    'listvalue3'.encode('ascii'),
                ]
            }
        else:
            expected = {
                u'key1': u'value1',
                u'key2': u'value2',
                u'key3': [
                    u'listvalue1',
                    u'listvalue2',
                    u'listvalue3',
                ]
            }

        self.assert_equal_deep(expected, Encoding.normalize(value))

    def test_to_ascii(self):
        """
        Test to ascii function
        :return:    void
        """

        value = {
            u'key1': 'value1'.encode('ascii'),
            'key2': u'value2',
            'key3'.encode('ascii'): [
                'listvalue1',
                'listvalue2'.encode('ascii'),
                u'listvalue3',
            ]
        }
        expected = {
            'key1'.encode('ascii'): 'value1'.encode('ascii'),
            'key2'.encode('ascii'): 'value2'.encode('ascii'),
            'key3'.encode('ascii'): [
                'listvalue1'.encode('ascii'),
                'listvalue2'.encode('ascii'),
                'listvalue3'.encode('ascii'),
            ]
        }

        self.assert_equal_deep(expected, Encoding.to_ascii(value))

    def test_to_unicode(self):
        """
        Test to unicode function
        :return:    void
        """

        value = {
            u'key1': 'value1'.encode('ascii'),
            'key2': u'value2',
            'key3'.encode('ascii'): [
                'listvalue1',
                'listvalue2'.encode('ascii'),
                u'listvalue3',
            ]
        }
        expected = {
            u'key1': u'value1',
            u'key2': u'value2',
            u'key3': [
                u'listvalue1',
                u'listvalue2',
                u'listvalue3',
            ]
        }

        self.assert_equal_deep(expected, Encoding.to_unicode(value))
