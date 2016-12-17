
import os
import re


class RuntimeEnvironment(object):
	"""
	The Google App Engine runtime environment
	"""

	def is_gae(self):
		"""
		Check if is Google App Engine
		"""

		if not os.environ.has_key('CURRENT_VERSION_ID'):
			return False

		if not os.environ.has_key('AUTH_DOMAIN'):
			return False

		if not os.environ.has_key('INSTANCE_ID'):
			return False

		if not os.environ.has_key('SERVER_SOFTWARE'):
			return False

		server_software = os.environ.get('SERVER_SOFTWARE')

		if not re.match(r'^Development\/', server_software) and not re.match(r'^Google App Engine\/', server_software):
			return False

		return True