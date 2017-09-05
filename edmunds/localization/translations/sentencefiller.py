
import re
import math
from edmunds.localization.translations.exceptions.sentencefillererror import SentenceFillerError


class SentenceFiller(object):
    """
    Sentence Filler
    """

    function_delimiter = '--'
    function_args_separator = ':'
    function_arg_separator = ','
    function_option_separator = '__'
    parameter_start_delimiter = '{'
    parameter_end_delimiter = '}'

    def fill_in(self, sentence, params=None):
        """
        Fill in the sentence
        :param sentence:    The sentence to fill in
        :type sentence:     str
        :param params:      The params to fill the sentence with
        :type params:       dict
        :return:            Filled in sentence
        :rtype:             str
        """

        if params is None:
            params = {}

        func_regex = '%s((?!%s).+?)%s' % (self.function_delimiter, self.function_delimiter, self.function_delimiter)
        sentence = re.sub(func_regex, lambda func: self._fill_in_function(func.group(1), params), sentence)

        sentence = self._fill_in_params(sentence, params)

        return sentence

    def _fill_in_function(self, func, params):
        """
        Fill in function
        :param func:    Function to fill in
        :type func:     str
        :param params:  The params to fill the sentence with
        :type params:   dict
        :return:        filled in function
        """

        args_options_regex = '^(?P<name>[a-zA-Z_]+)(?:%s(?P<args>.+?))?%s(?P<options>.+?)$' % (self.function_args_separator, self.function_option_separator)
        match = re.match(args_options_regex, func)
        if match is None:
            raise SentenceFillerError('Function "%s" was not valid.' % func)

        match_dict = match.groupdict()

        name = match_dict['name']
        options = match_dict['options'].split(self.function_option_separator)
        if 'args' in match_dict and match_dict['args'] is not None:
            args = match_dict['args'].split(self.function_arg_separator)
        else:
            args = []
        args = list(map(lambda arg: self._fill_in_params(arg, params), args))

        method_name = '_fill_in_%s_function' % name
        if not hasattr(self, method_name):
            raise SentenceFillerError('Using non-existing function "%s".' % name)
        func = getattr(self, method_name)(args, options)

        return func

    def _fill_in_params(self, value, params):
        """
        Fill in params
        :param value:   The value to fill in
        :type value:    str
        :param params:  The params to fill in with
        :type params:   dict
        :return:        The filled in value
        :rtype:         str
        """

        param_regex = '%s([a-zA-Z_]+?)%s' % (self.parameter_start_delimiter, self.parameter_end_delimiter)

        def fill_in_param(param):
            if param in params:
                return '%s' % params[param]
            raise SentenceFillerError('Param "%s" could not be replaced.' % param)

        value = re.sub(param_regex, lambda param: fill_in_param(param.group(1)), value)

        return value

    def _fill_in_plural_function(self, args, options):
        """
        Fill in a plural function
        :param args:    Arguments
        :type args:     list
        :param options: Options
        :type options:  list
        :return:        The correct option
        :rtype:         str
        """

        if len(args) != 1:
            raise SentenceFillerError('Plural-function requires exactly one argument.')

        try:
            count = int(args[0])
        except ValueError:
            raise SentenceFillerError('Plural-function argument was not an integer.')

        if count > len(options):
            return options[-1]

        if count <= 0:
            return options[0]

        return options[count - 1]

    def _fill_in_gender_function(self, args, options):
        """
        Fill in a gender function
        :param args:    Arguments
        :type args:     list
        :param options: Options
        :type options:  list
        :return:        The correct option
        :rtype:         str
        """

        if len(args) != 1:
            raise SentenceFillerError('Gender-function requires exactly one argument.')

        if len(options) != 2:
            raise SentenceFillerError('Gender-function requires exactly two options.')

        gender = '%s' % args[0]
        gender = gender.upper()

        if gender == 'M':
            return options[0]
        elif gender == 'F':
            return options[1]

        raise SentenceFillerError('Using unknown gender "%s".' % gender)
