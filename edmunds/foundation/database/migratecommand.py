
from edmunds.foundation.database.nomigratemanager import NoMigrateManager
from flask_migrate import MigrateCommand as FlaskMigrateCommand
from edmunds.database.providers.migrateserviceprovider import MigrateServiceProvider


def MigrateCommand(app):
    """
    Return the correct migrate command to use
    :param app: The current application
    :return:    The correct command
    """

    if not app.config('app.database.enabled', False):
        return NoMigrateManager(FlaskMigrateCommand)

    app.register(MigrateServiceProvider)

    return FlaskMigrateCommand
