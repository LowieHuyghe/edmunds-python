
from edmunds.console.command import Command
import nose
import sys
import os
from nose.core import TestProgram
from nose.config import all_config_files, Config
from nose.plugins.manager import DefaultPluginManager
from optparse import Option as OptParseOption
import click
import optparse


class TestCommand(Command):
    """
    Run the application's unittests
    """

    def __init__(self, name, app):
        """
        Init command
        :param name:    Name of the command
        :type name:     str
        :param app:     The application
        :type app:      edmunds.application.Application
        """
        super(TestCommand, self).__init__(name, app)

        # Fetch nose options
        config = Config(env=os.environ, files=all_config_files(), plugins=DefaultPluginManager())
        nose_options = config.getParser(doc=TestProgram.usage()).option_list

        # Override run-function to be able to load the click-options dynamically
        # Dynamic click.option does not work for class-methods because of __click_params__
        original_function = self.run
        def run_wrapper(**kwargs):
            return original_function(**kwargs)
        self.run = run_wrapper

        # Don't show --help
        nose_options = filter(lambda nose_option: '--help' not in nose_option._long_opts, nose_options)
        for nose_option in nose_options:
            args = nose_option._short_opts + nose_option._long_opts
            if not args:
                continue

            type_mapping = {
                'string': str,
                'int': int,
                'long': int,
                'float': float,
                # 'complex': str,
                # 'choice': str,
            }
            unsupported_attr = ['action', 'dest', 'const']

            kwargs = {}
            for attr in OptParseOption.ATTRS:
                if attr in unsupported_attr:
                    continue
                attr_value = getattr(nose_option, attr)
                if attr_value is None:
                    continue

                if attr == 'type':
                    attr_value = type_mapping[attr_value]
                    if nose_option.nargs > 1:
                        attr_value = click.Tuple([attr_value])
                if attr == 'default':
                    if attr_value == optparse.NO_DEFAULT:
                        continue

                kwargs[attr] = attr_value

            click.option(*args[:2], **kwargs)(run_wrapper)

    def run(self, **kwargs):
        """
        Run the command
        :param kwargs:  kwargs
        :return:        void
        """

        argv = sys.argv[:]
        while len(argv) and argv[0] != self.name:
            del argv[0]

        success = nose.run(argv=argv)
        if not success:
            sys.exit(1)
