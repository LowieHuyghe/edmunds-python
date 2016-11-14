
from test.TestCase import TestCase
from Edmunds.Config.Config import Config
import os
import Edmunds.Support.helpers as helpers


class ConfigTest(TestCase):
	"""
	Test the Config
	"""

	def setUp(self):
		"""
		Set up the test case
		"""

		super(ConfigTest, self).setUp()

		random_file = helpers.random_str(10)

		# Make config file
		self.config_file = os.path.join(self.app.config.root_path, 'config/%s.py' % random_file)

		# Make env file
		self.env_file = os.path.join(self.app.config.root_path, '.env.py')
		self.env_bak_file = os.path.join(self.app.config.root_path, '.env.%s.py' % random_file)
		os.rename(self.env_file, self.env_bak_file)

		# Make env testing file
		self.env_testing_file = os.path.join(self.app.config.root_path, '.env.testing.py')
		self.env_testing_bak_file = os.path.join(self.app.config.root_path, '.env.testing.%s.py' % random_file)
		os.rename(self.env_testing_file, self.env_testing_bak_file)

		# Make env production file
		self.env_production_file = os.path.join(self.app.config.root_path, '.env.production.py')
		self.env_production_bak_file = os.path.join(self.app.config.root_path, '.env.production.%s.py' % random_file)
		os.rename(self.env_production_file, self.env_production_bak_file)


	def tearDown(self):
		"""
		Tear down the test case
		"""

		super(ConfigTest, self).tearDown()

		# Set environment back to testing
		os.environ['APP_ENV'] = 'testing'

		# Remove config file
		if os.path.exists(self.config_file):
			os.remove(self.config_file)

		# Set backup env-file back
		if os.path.exists(self.env_bak_file):
			if os.path.exists(self.env_file):
				os.remove(self.env_file)
			os.rename(self.env_bak_file, self.env_file)

		# Set backup env-testing-file back
		if os.path.exists(self.env_testing_bak_file):
			if os.path.exists(self.env_testing_file):
				os.remove(self.env_testing_file)
			os.rename(self.env_testing_bak_file, self.env_testing_file)

		# Set backup env-production-file back
		if os.path.exists(self.env_production_bak_file):
			if os.path.exists(self.env_production_file):
				os.remove(self.env_production_file)
			os.rename(self.env_production_bak_file, self.env_production_file)


	def test_consistency(self):
		"""
		Test the consistency of the config
		"""

		data = [
			('got.sun',		'GOT_SUN',		'Jon Snow'					),
			('got.girl', 	'GOT_GIRL',		'Igritte'					),
			('got.enemy', 	'GOT_ENEMY',	('The', 'White', 'Walkers')	),
		]

		# Test data
		for row in data:
			key, old_key, value = row

			assert not self.app.config.has(key)
			assert None == self.app.config(key)
			assert old_key not in self.app.config

			self.app.config({
				key: value
			})

			assert self.app.config.has(key)
			assert value == self.app.config(key)
			assert value == self.app.config[old_key]


	def test_multiple(self):
		"""
		Test multiple assigns at once
		"""

		data = [
			('got.sun',		'GOT_SUN',		'Jon Snow'					),
			('got.girl', 	'GOT_GIRL',		'Igritte'					),
			('got.enemy', 	'GOT_ENEMY',	('The', 'White', 'Walkers')	),
		]

		# Make update dictionary
		update = {}
		for row in data:
			key, old_key, value = row

			update[old_key] = value

		# Update
		self.app.config(update)

		# Test data
		for row in data:
			key, old_key, value = row

			assert self.app.config.has(key)
			assert value == self.app.config(key)
			assert value == self.app.config[old_key]


	def test_config_file(self):
		"""
		Test config file
		"""

		data = [
			('got.sun',		'GOT_SUN',		'Jon Snow',						"'Jon Snow'"					),
			('got.girl', 	'GOT_GIRL',		'Igritte',						"'Igritte'"						),
			('got.enemy', 	'GOT_ENEMY',	('The', 'White', 'Walkers'),	"('The', 'White', 'Walkers')"	),
		]

		# Make config file
		with open(self.config_file, 'w+') as f:
			for row in data:
				key, old_key, value, str_value = row
				f.write("%s = %s\n" % (old_key, str_value))

		# Make app
		app = self.create_application()

		# Check config
		for row in data:
			key, old_key, value, str_value = row

			assert app.config.has(key)
			assert value == app.config(key)
			assert value == app.config[old_key]


	def test_env_file(self):
		"""
		Test env file
		"""

		data = [
			('got.sun',		'GOT_SUN',		'Jon Snow',						"'Jon Snow'"					),
			('got.girl', 	'GOT_GIRL',		'Igritte',						"'Igritte'"						),
			('got.enemy', 	'GOT_ENEMY',	('The', 'White', 'Walkers'),	"('The', 'White', 'Walkers')"	),
		]

		# Make config file
		with open(self.env_file, 'w+') as f:
			for row in data:
				key, old_key, value, str_value = row
				f.write("%s = %s\n" % (old_key, str_value))

		# Make app
		app = self.create_application()

		# Check config
		for row in data:
			key, old_key, value, str_value = row

			assert app.config.has(key)
			assert value == app.config(key)
			assert value == app.config[old_key]


	def test_env_testing_file(self):
		"""
		Test env  file
		"""

		data = [
			('got.sun',		'GOT_SUN',		'Jon Snow',						"'Jon Snow'"					),
			('got.girl', 	'GOT_GIRL',		'Igritte',						"'Igritte'"						),
			('got.enemy', 	'GOT_ENEMY',	('The', 'White', 'Walkers'),	"('The', 'White', 'Walkers')"	),
		]

		# Make config file
		with open(self.env_testing_file, 'w+') as f:
			for row in data:
				key, old_key, value, str_value = row
				f.write("%s = %s\n" % (old_key, str_value))

		# Make app
		app = self.create_application()

		# Check config
		for row in data:
			key, old_key, value, str_value = row

			assert app.config.has(key)
			assert value == app.config(key)
			assert value == app.config[old_key]


	def test_file_priority(self):
		"""
		Test priority of config
		"""

		key = 'got.season'
		old_key = 'GOT_SEASON'

		# Make config file
		with open(self.config_file, 'w+') as f:
			f.write("%s = %d" % (old_key, 1))

		# Make app
		app = self.create_application()

		# Check config
		assert app.config.has(key)
		assert 1 == app.config(key)
		assert 1 == app.config[old_key]

		# Make env file
		with open(self.env_file, 'w+') as f:
			f.write("%s = %d" % (old_key, 2))

		# Make app
		app = self.create_application()

		# Check config
		assert app.config.has(key)
		assert 2 == app.config(key)
		assert 2 == app.config[old_key]

		# Make env testing file
		with open(self.env_testing_file, 'w+') as f:
			f.write("%s = %d" % (old_key, 3))

		# Make app
		app = self.create_application()

		# Check config
		assert app.config.has(key)
		assert 3 == app.config(key)
		assert 3 == app.config[old_key]


	def test_setting_environment(self):
		"""
		Test setting the environment
		"""

		value = 'nice'
		str_value = "'nice'"
		key = 'got.niveau'
		old_key = 'GOT_NIVEAU'

		# Make env file
		with open(self.env_production_file, 'w+') as f:
			f.write("%s = %s\n" % (old_key, str_value))

		# Set environment value
		os.environ['APP_ENV'] = 'production'

		# Make app
		app = self.create_application()

		# Check config
		assert app.config.has(key)
		assert value == app.config(key)
		assert value == app.config[old_key]