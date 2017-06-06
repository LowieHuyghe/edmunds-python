
from edmunds.console.command import Command, Option
import nose
import sys
import os
from nose.core import TestProgram
from nose.config import all_config_files, Config
from nose.plugins.manager import DefaultPluginManager
from optparse import Option as OptParseOption


def get_test_command_options():
    """
    Get test command options
    :return:    The options
    """

    # Fetch nose options
    config = Config(env=os.environ, files=all_config_files(), plugins=DefaultPluginManager())
    nose_options = config.getParser(doc=TestProgram.usage()).option_list

    # Don't show --help
    nose_options = filter(lambda nose_option: '--help' not in nose_option._long_opts, nose_options)

    # Map nose-option to Option
    def map_nose_option(nose_option):
        args = nose_option._short_opts + nose_option._long_opts
        kwargs = {}
        for attr in OptParseOption.ATTRS:
            if attr not in ['type'] and hasattr(nose_option, attr) and getattr(nose_option, attr) is not None:
                kwargs[attr] = getattr(nose_option, attr)
        return Option(*args, **kwargs)
    return list(map(map_nose_option, nose_options))


class TestCommand(Command):
    """
    Run the application's unittests
    """

    option_list = get_test_command_options()

    def run(self, **kwargs):
        """
        Run the command
        :param kwargs:  kwargs
        :return:        void
        """

        argv = sys.argv[:]
        while len(argv) and argv[0] != 'test':
            del argv[0]

        nose.run(argv=argv)
