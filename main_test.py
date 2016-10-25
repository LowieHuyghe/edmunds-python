
if __name__ == '__main__':

	import sys
	sys.path.append('lib')
	sys.path.append('src')


from bootstrap import test
import unittest

suite = test.bootstrap(__file__)


if __name__ == '__main__':

	runner = unittest.TextTestRunner()
	runner.run(suite)