
from edmunds.support.serviceprovider import ServiceProvider
from edmunds.profiler.middleware.profilermiddleware import ProfilerMiddleware


class ProfilerServiceProvider(ServiceProvider):
	"""
	Profiler Service Provider
	"""

	def register(self):
		"""
		Register the service provider
		"""

		self.app.middleware(ProfilerMiddleware)
