
from Edmunds.Support.ServiceProvider import ServiceProvider
from Edmunds.Log.LogManager import LogManager


class LogServiceProvider(ServiceProvider):
	"""
	Log Service Provider
	"""

	def register(self):
		"""
		Register the service provider
		"""

		# Make manager and load instance
		self._manager = LogManager(self.app)

		# Add each instance
		for instance in self._manager.all():
			self.app.logger.addHandler(instance)