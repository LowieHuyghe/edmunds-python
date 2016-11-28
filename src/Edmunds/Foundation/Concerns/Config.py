
class Config(object):
	"""
	This class concerns config code for Application to extend from
	"""

	def _init_config(self, config_dirs = None):
		"""
		Initiate the configuration
		:param config_dirs: 	Configuration directories
		:type  config_dirs: 	list
		"""

		# Configuration directories
		if config_dirs == None:
			config_dirs = [
				'lib/edmunds/src/config',
				'config',
			]

		# Load config
		self.config.load_all(config_dirs)
