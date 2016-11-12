
from flask.config import Config as FlaskConfig


class Config(FlaskConfig):
	"""
	Config module
	"""

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

		return '.'.join(key.split('.')).upper()