
from Edmunds.Support.ServiceProvider import ServiceProvider
from Edmunds.Profiler.Middleware.ProfilerMiddleware import ProfilerMiddleware


class ProfilerServiceProvider(ServiceProvider):
	"""
	Profiler Service Provider
	"""

	def register(self):
		"""
		Register the service provider
		"""

		self.app.middleware(ProfilerMiddleware)
