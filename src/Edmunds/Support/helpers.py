
import importlib
import os
import random
import string


def get_class(className):
	"""
	Get class from className
	:param className: 	The class-name
	:type  className: 	string
	:return: 			The class
	:rtype: 			class
	"""

	(module, className) = get_module_and_class(className)

	return getattr(importlib.import_module(module), className)


def get_module_and_class(className):
	"""
	Get module and className from given className
	:param className: 	The className
	:type  className: 	string
	:return: 			The module and class-name
	:rtype: 			tuple
	"""

	parts = className.split('.')
	module = className
	className = parts[-1]

	return (module, className)


def get_full_class_name(class_):
	"""
	Get full class name of class
	:param class_: 	The class
	:type  class_: 	class
	:return: 		The full class name
	:rtype: 		string
	"""

	return class_.__module__ + '.' + class_.__name__


def get_dir_from_file(file):
	"""
	Get the directory of the given file
	:param file: 	The FIle
	:type  file: 	str
	:return: 		The directory
	:rtype: 		str
	"""

	return os.path.dirname(os.path.realpath(file))


def random_str(length):
	"""
	Get a random string
	:param length: 	Length of the random string
	:type  length: 	int
	:return: 		Random string
	:rtype: 		str
	"""

	return ''.join(random.SystemRandom().choice(string.ascii_uppercase + string.digits) for _ in range(length))
