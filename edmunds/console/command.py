
from edmunds.globals import ABC, abc


class Command(ABC):

    def __init__(self, name, app):
        """
        Init command
        :param name:    Name of the command
        :type name:     str
        :param app:     The application
        :type app:      edmunds.application.Application
        """
        super(Command, self).__init__()

        self.name = name
        self.app = app

    @abc.abstractmethod
    def run(self, *args, **kwargs):
        """
        Object
        :return:    void
        """
        pass
