
import Edmunds.Support.helpers as helpers
from Edmunds.Application import Application
import unittest
import os
import re


def bootstrap(rootFile):
	"""
	Bootstrap the test-run
	:return: 	The test-suit to test
	:rtype:		TestSuite
	"""

	rootDir = helpers.getDirFromFile(rootFile)

	suites = []

	for root, subdirs, files in os.walk('test'):
		for file in files:
			if file == '__init__.py':
				continue

			match = re.match(r'(^.*?)\.py$', file)
			if not match:
				continue

			file = os.path.join(root, match.group(1))
			testClassName = '.'.join(file.split('/'))
			testClass = helpers.getClass(testClassName)

			suite = unittest.TestLoader().loadTestsFromTestCase(testClass)
			suites.append(suite)

	suite = unittest.TestSuite(suites)

	return suite
