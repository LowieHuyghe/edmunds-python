
from Edmunds.Foundation.Testing.TestCase import TestCase
from Edmunds.Support.ServiceProvider import ServiceProvider


class ServiceProviderTest(TestCase):
	"""
	Test the Service Provider
	"""

	def testNoAbstractRegister(self):
		"""
		Test if abstract register method is required
		"""

		try:
			MyServiceProviderNoAbstractRegister(self.app)
			assert False

		except TypeError as e:
			if 'register' in e.message:
				assert True
			else:
				raise e


	def testAbstractRegister(self):
		"""
		Test required abstract register method
		"""

		MyServiceProviderAbstractRegister(self.app)
		assert True



class MyServiceProviderNoAbstractRegister(ServiceProvider):
	"""
	Service Provider class with missing register method
	"""

	pass


class MyServiceProviderAbstractRegister(ServiceProvider):
	"""
	Service Provider class with register method
	"""

	def register(self):
		pass