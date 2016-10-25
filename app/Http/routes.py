
def route(app):
	"""
	Define the routes in the application
	:rtype:		None
	"""

	@app.route('/')
	def index():
		return 'Hello world!'
