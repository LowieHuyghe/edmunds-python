
if __name__ == '__main__':

	import sys
	sys.path.append('lib')
	sys.path.append('src')


from bootstrap import test
import unittest
import sys


testLocations = []

if len(sys.argv) > 1:
	testLocations += sys.argv[1::]
else:
	testLocations.append('test')

suite = test.bootstrap(__file__, testLocations)


if __name__ == '__main__':

	runner = unittest.TextTestRunner()
	runner.run(suite)