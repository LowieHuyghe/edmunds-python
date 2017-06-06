
from tests.testcase import TestCase
from edmunds.foundation.testing.testcommand import TestCommand
from edmunds.console.command import Command
import mock
from nose.core import TestProgram
from nose.config import all_config_files, Config
from nose.plugins.manager import DefaultPluginManager
import os


class TestTestCommand(TestCase):
    """
    Test test command
    """

    def test_command(self):
        """
        Test command
        :return:    void
        """

        command = TestCommand()
        self.assert_is_instance(command, Command)

        with mock.patch('nose.run', return_value=None):
            command.run()

    def test_options(self):
        """
        Test options
        :return:    void
        """

        # Test command options
        command = TestCommand()
        command_options = command.option_list

        # Nose command options
        env = os.environ
        cfg_files = all_config_files()
        manager = DefaultPluginManager()
        config = Config(env=env, files=cfg_files, plugins=manager)
        nose_options = config.getParser(doc=TestProgram.usage()).option_list
        # Don't show --help
        nose_options = list(filter(lambda nose_option: '--help' not in nose_option._long_opts, nose_options))

        # Compare
        self.assert_less(0, len(command_options))
        for i in range(0, len(command_options)):
            command_option = command_options[i]
            nose_option = nose_options[i]

            self.assert_list_equal(nose_option._short_opts + nose_option._long_opts, list(command_option.args))
