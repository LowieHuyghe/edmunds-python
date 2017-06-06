
from edmunds.console.command import Command
import nose
import sys


class TestCommand(Command):
    """
    Run the application's unittests
    """

    def run(self):
        """
        Run the command
        :return:    void
        """

        argv = sys.argv[:]
        while len(argv) and argv[0] != 'test':
            del argv[0]

        nose.run(argv=argv)
