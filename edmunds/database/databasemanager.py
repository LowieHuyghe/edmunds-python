
from edmunds.foundation.patterns.manager import Manager
from flask_sqlalchemy import SQLAlchemy
from edmunds.database.drivers.mysql import MySql


class DatabaseManager(Manager):
    """
    Database Manager
    """

    _sql_alchemy_instance = None

    @staticmethod
    def get_sql_alchemy_instance():
        """
        Get sql alchemy instance
        :return:    SQLAlchemy
        :rtype:     SQLAlchemy
        """

        if DatabaseManager._sql_alchemy_instance is None:
            DatabaseManager._sql_alchemy_instance = SQLAlchemy()
        return DatabaseManager._sql_alchemy_instance

    def __init__(self, app):
        """
        Initiate the manager
        :param app:             The application
        :type  app:             Application
        """

        super(DatabaseManager, self).__init__(app, app.config('app.database.instances', []))

        self._files_path = 'database'

        self._init_sql_alchemy()

    def _init_sql_alchemy(self):
        """
        Init sql alchemy
        :return:    void
        """

        database_uri = None
        binds = {}

        for instances_config_item in self._instances_config:
            instance_database_uri = None

            if instances_config_item['driver'] == MySql:
                if 'user' not in instances_config_item \
                        or 'pass' not in instances_config_item \
                        or 'host' not in instances_config_item \
                        or 'table' not in instances_config_item:
                    raise RuntimeError("Database-driver '%s' is missing some configuration ('user', 'pass', 'host' and 'table' are required)." % instances_config_item['name'])

                mysql_user = instances_config_item['user']
                mysql_pass = instances_config_item['pass']
                mysql_host = instances_config_item['host']
                mysql_table = instances_config_item['table']
                mysql_port = instances_config_item['port'] if 'port' in instances_config_item else 3306

                instance_database_uri = 'mysql://%s:%s@%s:%s/%s' % (mysql_user, mysql_pass, mysql_host, mysql_port, mysql_table)

            binds[instances_config_item['name']] = instance_database_uri
            if database_uri is None:
                database_uri = instance_database_uri

        self._app.config.update({
            'SQLALCHEMY_DATABASE_URI': database_uri,
            'SQLALCHEMY_BINDS': binds,
            'SQLALCHEMY_TRACK_MODIFICATIONS': False
        })

        if 'sqlalchemy' in self._app.extensions:
            raise RuntimeError('SQLAlchemy was already registered for this application')

        DatabaseManager.get_sql_alchemy_instance().app = self._app
        DatabaseManager.get_sql_alchemy_instance().init_app(self._app)

    def _create_my_sql(self, config):
        """
        Create my sql
        :param config:  The config
        :return:        SQLAlchemy Engine
        :rtype:         sqlalchemy.engine.base.Engine
        """

        db = DatabaseManager.get_sql_alchemy_instance()
        return db.get_engine(bind=config['name'])
