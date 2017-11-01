
import random
import string
import re
from edmunds.encoding.encoding import Encoding


def random_str(length):
    """
    Get a random string
    :param length:  Length of the random string
    :type  length:  int
    :return:        Random string
    :rtype:         str
    """

    return Encoding.normalize(''.join(random.choice(string.ascii_lowercase + string.digits) for _ in range(length)))


def random_int(min, max):
    """
    Get random integer
    :param min: Minimum value (included)
    :param max: Maximum value (included)
    :return:    Random integer
    """

    return random.randint(min, max)


def snake_case(camel_case):
    """
    Transform camel was to snake case
    :param camel_case:      Camel case string
    :type  camel_case:      str
    :return:                Snake case string
    :rtype:                 str
    """

    snake_case = re.sub('(.)([A-Z][a-z]+)', r'\1_\2', camel_case)
    snake_case = re.sub('([a-z0-9])([A-Z])', r'\1_\2', snake_case).lower()

    return snake_case
