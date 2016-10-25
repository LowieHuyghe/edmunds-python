
if __name__ == '__main__':

	import sys
	sys.path.append('lib')
	sys.path.append('src')


from bootstrap import edmunds

app = edmunds.bootstrap(__file__)


if __name__ == '__main__':

	app.run()