
from edmunds.console.manager import Manager, FlaskManager
from edmunds.foundation.database.nomigratecommand import NoMigrateCommand


class NoMigrateManager(Manager):
    """
    Perform database migrations (DATABASE DISABLED)
    """

    def __init__(self, manager):
        """
        Constructor
        :param manager: The manager to disable 
        """

        usage = '%s (DATABASE DISABLED)' % manager.usage

        super(NoMigrateManager, self).__init__(usage=usage)

        self._options = list(manager._options)
        self._commands = dict(manager._commands)
        self._override_commands()

    def _override_commands(self):
        """
        Override own commands
        :return:    void
        """

        for command_name in self._commands:
            command = self._commands[command_name]
            if isinstance(command, FlaskManager):
                command = NoMigrateManager(command)
            else:
                command = NoMigrateCommand()
            self._commands[command_name] = command
