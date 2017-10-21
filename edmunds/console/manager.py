
import os
import sys
import re
from subprocess import Popen
from edmunds.globals import ABC, abc


class Manager(ABC):

    def __init__(self, app):
        """
        The constructor
        :param app: The application
        :type app:  edmunds.application.Application
        """

        self.app = app

    def init(self, script_file):
        """
        Initiate the manager
        :param script_file: The script file
        :type script_file:  str
        :return:    void
        """

        if 'FLASK_APP' not in os.environ:
            file_path = os.path.abspath(script_file)
            debug_param = 1 if self.app.debug else 0

            arguments = []
            for index in range(0, len(sys.argv)):
                arg = sys.argv[index]
                if arg == 'flask' or file_path.endswith(arg):
                    arguments = sys.argv[index + 1:]
                    break
            for index in range(0, len(arguments)):
                if re.search(r'\s', arguments[index]):
                    arguments[index] = '\'%s\'' % arguments[index].replace('\'', '\'"\'"\'')

            command = 'FLASK_APP=%s FLASK_DEBUG=%i python -m flask %s' % (file_path, debug_param, ' '.join(arguments))

            sys.exit(Popen(command, shell=True).wait())

        self.add_commands()

    def add_command(self, name, class_):
        """
        Add a command
        :param name:    The name of the command
        :type name:     str
        :param class_:  The class of the command
        :type class_:   class
        :return:        void
        """

        instance = class_(name, self.app)
        decorator = self.app.cli.command(name=name, help=instance.__doc__)
        decorator(instance.run)

    @abc.abstractmethod
    def add_commands(self):
        """
        Add the commands
        :return:    void
        """
        pass
