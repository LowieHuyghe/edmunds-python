
from flask.config import Config as FlaskConfig
import os


class Config(FlaskConfig):
	"""
	Config module
	"""

	def __init__(self, root_path, defaults=None):
		"""
		Initiate the dictionary
		:param root_path: 	The root path
		:type  root_path: 	str
		:param defaults: 	The default
		:type  defaults: 	mixed
		"""

		super(Config, self).__init__(root_path, defaults)

		self.loaded_config = []


	def __call__(self, mixed, default = None):
		"""
		Get a value or update some values
		:param mixed: 		Key of dictionary
		:type mixed: 		dict|key
		:param default: 	The default value when fetching a value
		:type  default: 	mixed
		:return: 			Respectively the value and None
		:rtype: 			mixed|None
		"""

		# Fetch the namespace and load them
		self._load_namespace_from_call(mixed)

		# Update dictionary
		if isinstance(mixed, dict):
			processed_dict = {}

			for key in mixed:
				processed_key = self._getProcessKey(key)
				processed_dict[processed_key] = mixed[key]

			return self.update(processed_dict)

		# Get value
		else:
			if not self.has(mixed):
				return default

			processed_key = self._getProcessKey(mixed)
			return self[processed_key]


	def has(self, key):
		"""
		Check if has key
		:param key: 	The key
		:type  key: 	str
		:return: 		Has key?
		:rtype: 		boolean
		"""

		processed_key = self._getProcessKey(key)
		return processed_key in self


	def _getProcessKey(self, key):
		"""
		Process the given key
		:param key: 	The key to process
		:type  key: 	str
		:return: 		The processed key
		:rtype: 		str
		"""

		return '_'.join(key.split('.')).upper()


	def _load_namespace_from_call(self, mixed):
		"""
		Load namespace from call
		:param mixed: 		Key of dictionary
		:type mixed: 		dict|key
		"""

		namespaces = []
		if isinstance(mixed, dict):
			for key in mixed:
				namespace = key.split('.')[0]
				if key not in namespaces:
					namespaces.append(namespace)
		else:
			namespace = mixed.split('.')[0]
			namespaces.append(namespace)

		for namespace in namespaces:
			if namespace not in self.loaded_config:
				self.loaded_config.append(namespace)
				config_file = 'config/%s.py' % namespace
				config_full_file_path = os.path.join(self.root_path, config_file)

				print config_full_file_path

				if os.path.isfile(config_full_file_path):
					self.from_pyfile(config_file)