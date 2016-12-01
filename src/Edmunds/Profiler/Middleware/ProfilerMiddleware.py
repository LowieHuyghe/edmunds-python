
from Edmunds.Foundation.ApplicationMiddleware import ApplicationMiddleware
from cProfile import Profile
import time
import datetime


class ProfilerMiddleware(ApplicationMiddleware):
	"""
	Profiler Middleware
	"""

	def __init__(self, app):
		"""
		Initialize the application
		:param app: 	The application
		:type  app: 	Edmunds.Application
		"""

		super(ProfilerMiddleware, self).__init__(app)

		# Initials
		self._default_profile_dir = 'storage/profs'


	def handle(self, environment, start_response):
		"""
		Handle the middleware
		:param environment: 	The environment
		:type  environment: 	Environment
		:param start_response: 	The application
		:type  start_response: 	flask.Response
		"""

		# Get and run app through profiler
		profiler, start, end, body = self._get_profiler_and_return(environment, start_response)

		# Compose suggestive file name
		suggestive_file_name = '%s.%s.%s.prof' % (
									datetime.datetime.fromtimestamp(start).strftime('%Y_%m_%d.%H_%M_%S'),
									environment['REQUEST_METHOD'],
									environment.get('PATH_INFO').strip(
										'/').replace('/', '.') or 'root'
								 )

		# Process profiler with every profiling instance
		for instance in self.app.config('app.profiler.instances', []):
			driver = instance['driver'](instance, self._default_profile_dir)
			driver.process(profiler, start, end, environment, suggestive_file_name)

		return [ body ]


	def _get_profiler_and_return(self, environment, start_response):
		"""
		Handle the middleware
		:param environment: 	The environment
		:type  environment: 	Environment
		:param start_response: 	The application
		:type  start_response: 	flask.Response
		:return:				Profiler, start, end, and body
		:rtype: 				tuple
		"""

		response_body = []

		def catching_start_response(status, headers, exc_info=None):
			start_response(status, headers, exc_info)
			return response_body.append

		def runapp():
			appiter = self.wsgi_app(environment, catching_start_response)
			response_body.extend(appiter)
			if hasattr(appiter, 'close'):
				appiter.close()

		p = Profile()
		start = time.time()
		p.runcall(runapp)
		body = b''.join(response_body)
		end = time.time()

		return (p, start, end, body)