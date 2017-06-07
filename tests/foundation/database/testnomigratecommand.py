
from tests.testcase import TestCase
from edmunds.console.command import Command
from edmunds.foundation.database.nomigratecommand import NoMigrateCommand
import mock
import sys
if sys.version_info < (3, 0):
    from cStringIO import StringIO
else:
    from io import StringIO


class TestNoMigrateCommand(TestCase):
    """
    Test the no migrate command
    """

    def test_command(self):
        """
        Test command
        :return:    void
        """

        command = NoMigrateCommand()

        self.assert_is_instance(command, Command)

        with mock.patch('sys.stdout', new_callable=StringIO) as output_stream:
            with self.assert_raises_regexp(SystemExit, '1'):
                command.run()
            self.assert_in('Database is not enabled in the application.', output_stream.getvalue())
