
import os.path
import pprint
import re
import setuptools
import sys
try:
    import ConfigParser
except ImportError:
    import configparser as ConfigParser
try:
    from urllib2 import urlopen, URLError
except ImportError:
    from urllib.request import urlopen
    from urllib.error import URLError


# The path to the classifiers and the config
classifiers_path = 'setup.classifiers.txt'
config_path = 'setup.config.ini'


# Setup-object
class Setup(object):

    def __init__(self, base_path, classifiers_path, config_path):
        """
        Construct setup
        :param base_path:           The base path
        :param classifiers_path:    Path to the classifiers
        :param config_path:         Path to the config
        """

        self.base_path = base_path

        self.classifiers = self._load_classifiers(classifiers_path)
        self.config = self._load_config(config_path)

    def _load_classifiers(self, classifiers_path):
        """
        Load classifiers
        :param classifiers_path:    Path to the classifiers
        :return:                    list
        """

        classifiers_path = os.path.join(self.base_path, classifiers_path)

        # Get classifiers if not already fetched
        if not os.path.isfile(classifiers_path):
            try:
                url = 'https://pypi.python.org/pypi?%3Aaction=list_classifiers'
                response = urlopen(url)
                classifiers_data = response.read().decode('utf-8')
            except URLError:
                self.exit('Could not fetch classifiers from \'%s\'' % url)

            with open(classifiers_path, 'w') as classifiers_file:
                classifiers_file.write('# See https://pypi.python.org/pypi?%3Aaction=list_classifiers\n')
                classifiers_file.write(classifiers_data)

        # Read classifiers
        classifiers = []
        with open(classifiers_path, 'r') as classifiers_file:
            for classifier_line in classifiers_file:
                classifier_match = re.search('^([^#]*)', classifier_line)
                if not classifier_match:
                    continue
                classifier = classifier_match.group(1).strip()
                if not classifier:
                    continue
                classifiers.append(classifier)

        if not classifiers:
            self.exit('No classifiers were found in \'%s\'\nDelete the file and try again.' % classifiers_path)

        return classifiers

    def _load_config(self, config_path):
        """
        Load the config
        :param config_path: Path to the config
        :return:            Config-dict
        """

        config_parser = ConfigParser.ConfigParser()
        config_parser.read(os.path.join(self.base_path, config_path))

        config = {}
        for section in config_parser.sections():
            config[section.lower()] = {}
            for option in config_parser.options(section):
                config[section.lower()][option.lower()] = config_parser.get(section, option)

        return config

    def _process_argument(self, arguments, argument_key, config_section, config_key, value_type):
        """
        Process argument
        :param arguments:       The dict of arguments to add to
        :param argument_key:    The key to add to the dict
        :param config_section:  The config section
        :param config_key:      The config key
        :param value_type:      The value type
        :return:                void
        """

        value = self._process_value(config_section, config_key, value_type)
        if value is None:
            return

        arguments[argument_key] = value

    def _process_classifier(self, classifiers, config_section, config_key, value_type, regex):
        """
        Process classifier
        :param classifiers:     The list of classifiers to add to
        :param config_section:  The config section
        :param config_key:      The config key
        :param value_type:      The value type
        :param regex:           The regex to match
        :return:                void
        """

        value = self._process_value(config_section, config_key, value_type)
        if value is None:
            return

        if not isinstance(value, list):
            value = [value]

        for item in value:
            matched_classifiers = []
            for classifier in self.classifiers:
                if re.match(regex.replace('{0}', item.replace(' ', '[\s:]*?')), classifier):
                    matched_classifiers.append(classifier)

            if not matched_classifiers:
                self.exit('No classifiers found for: \'%s\' > \'%s\' > \'%s\'' % (config_section, config_key, item))
            elif len(matched_classifiers) > 1:
                self.exit('Multiple classifiers found for: \'%s\' > \'%s\' > \'%s\'' % (config_section, config_key, item))
            else:
                classifiers.extend(matched_classifiers)

    def _process_value(self, config_section, config_key, value_type):
        """
        Process the value
        :param config_section:  The section in the config
        :param config_key:      The key in the config
        :param value_type:      The type of the value
        :return:                The processed value
        """

        if config_section not in self.config:
            return None
        if config_key not in self.config[config_section]:
            return None

        value = self.config[config_section][config_key]

        if value_type == str:
            if value.startswith('file://'):
                value_path = os.path.join(self.base_path, value[len('file://'):])
                with open(value_path, 'r') as value_file:
                    value = value_file.read()
        elif value_type == list:
            if value.startswith('file://'):
                value_path = os.path.join(self.base_path, value[len('file://'):])
                with open(value_path, 'r') as value_file:
                    value = value_file.readlines()
                    value = filter(lambda k: bool(k), value)
                    value = map(lambda k: k.strip().replace('\n', ''), value)
            else:
                value = value.split(',')

        return value

    def _load_arguments(self):
        """
        Get the setup arguments
        :return:    The setup arguments
        """

        setup_arguments = dict()

        # General
        self._process_argument(setup_arguments, 'name', 'general', 'name', str)
        self._process_argument(setup_arguments, 'version', 'general', 'version', str)
        self._process_argument(setup_arguments, 'description', 'general', 'description', str)
        self._process_argument(setup_arguments, 'long_description', 'general', 'long_description', str)
        self._process_argument(setup_arguments, 'url', 'general', 'url', str)
        self._process_argument(setup_arguments, 'license', 'general', 'license', str)
        self._process_argument(setup_arguments, 'install_requires', 'general', 'requirements', list)

        # Author
        self._process_argument(setup_arguments, 'author', 'author', 'name', str)
        self._process_argument(setup_arguments, 'author_email', 'author', 'email', str)

        # Classifiers
        classifiers = []
        self._process_classifier(classifiers, 'classifiers', 'status', list, '^Development Status :: ({0} - \w+|\d+ - {0})')
        self._process_classifier(classifiers, 'classifiers', 'programming_languages', list, '^Programming Language :: {0}$')
        self._process_classifier(classifiers, 'classifiers', 'audiences', list, '^Intended Audience :: .*?{0}.*?')
        self._process_classifier(classifiers, 'classifiers', 'topics', list, '^Topic :: .*?{0}.*?')
        self._process_classifier(classifiers, 'classifiers', 'license', list, '^License :: .*?{0}.*?')
        if classifiers:
            setup_arguments['classifiers'] = list(set(classifiers))

        # Setup
        self._process_argument(setup_arguments, 'setup_requires', 'setup', 'requirements', list)

        # Tests
        self._process_argument(setup_arguments, 'tests_require', 'tests', 'requirements', list)
        self._process_argument(setup_arguments, 'test_suite', 'tests', 'suite', str)

        # Packages
        packages_arguments = dict()
        self._process_argument(packages_arguments, 'exclude', 'packages', 'exclude', list)
        self._process_argument(packages_arguments, 'include', 'packages', 'include', list)
        if packages_arguments:
            setup_arguments['packages'] = setuptools.find_packages(**packages_arguments)

        return setup_arguments

    def exit(self, reason, exitcode=1):
        """
        Exit the program
        :param reason:      The reason
        :param exitcode:    The exitcode
        :return:            void
        """

        if reason:
            print('\033[0;31m%s\033[0m' % reason)
        sys.exit(exitcode)

    def setup(self):
        """
        Setup
        :return:    void
        """

        arguments = self._load_arguments()

        if '--debug' in sys.argv:
            print('Loading setup with the following kwargs:')
            pp = pprint.PrettyPrinter(indent=4, depth=4)
            pp.pprint(arguments)

        else:
            setuptools.setup(**arguments)


# Construct and setup
base_path = os.path.abspath(os.path.dirname(__file__))
Setup(base_path, classifiers_path, config_path).setup()
