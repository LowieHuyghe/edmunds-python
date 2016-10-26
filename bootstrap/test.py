
import Edmunds.Support.helpers as helpers
from Edmunds.Application import Application
import unittest
import os
import re
import sys


def bootstrap(rootFile, testLocations):
	"""
	Bootstrap the test-run
	:return: 	The test-suit to test
	:rtype:		TestSuite
	"""

	rootDir = helpers.getDirFromFile(rootFile)

	suites = []

	for testLocation in testLocations:

		# Load tests from file or directory
		if os.path.exists(testLocation):
			# Directory
			if os.path.isdir(testLocation):
				for root, subdirs, files in os.walk(testLocation):
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
			# File
			else:
				match = re.match(r'(^.*?)\.py$', testLocation)
				if not match:
					continue

				file = match.group(1)
				testClassName = '.'.join(file.split('/'))
				testClass = helpers.getClass(testClassName)

				suite = unittest.TestLoader().loadTestsFromTestCase(testClass)
				suites.append(suite)

		# TestClassName given
		else:
			testClass = helpers.getClass(testLocation)

			suite = unittest.TestLoader().loadTestsFromTestCase(testClass)
			suites.append(suite)

	suite = unittest.TestSuite(suites)

	return suite