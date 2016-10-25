
import Edmunds.Support.helpers as helpers
from Edmunds.Application import Application


def bootstrap(rootFile):
	"""
	Bootstrap the Application
	:return: 	The bootstrapped application
	:rtype:		Application
	"""

	rootDir = helpers.getDirFromFile(rootFile)

	app = Application()

	return app