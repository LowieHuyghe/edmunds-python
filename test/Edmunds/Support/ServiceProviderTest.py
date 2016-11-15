
from test.TestCase import TestCase
from Edmunds.Support.ServiceProvider import ServiceProvider


class ServiceProviderTest(TestCase):
	"""
	Test the Service Provider
	"""

	def testNoAbstractRegister(self):
		"""
		Test if abstract register method is required
		"""

		with self.assert_raises_regexp(TypeError, 'register'):
			MyServiceProviderNoAbstractRegister(self.app)


	def testAbstractRegister(self):
		"""
		Test required abstract register method
		"""

		self.assert_is_instance(MyServiceProviderAbstractRegister(self.app), MyServiceProviderAbstractRegister)



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