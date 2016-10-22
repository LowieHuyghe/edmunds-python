
from flask import Flask
from app.Http import routes
from werkzeug.debug import DebuggedApplication


class Application(Flask):
	"""
	The Edmunds Application
	"""

	def __init__(self):
		"""
		Initialize the application
		"""

		super(Application, self).__init__(__name__)

		self.debug = True
		self.wsgi_app = DebuggedApplication(self.wsgi_app, True)

		routes.route(self)