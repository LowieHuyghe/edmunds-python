
from edmunds.console.command import Command
import sys


class NoMigrateCommand(Command):

    def run(self, *args, **kwargs):
        """
        Run
        :param args:    The args 
        :param kwargs:  The kwargs
        :return:        void
        """

        print('Database is not enabled in the application.')

        sys.exit(1)
