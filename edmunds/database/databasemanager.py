
from edmunds.foundation.patterns.manager import Manager
from flask_sqlalchemy import SQLAlchemy
from edmunds.database.drivers.mysql import MySql
from edmunds.database.drivers.postgresql import PostgreSql
from edmunds.database.drivers.sqlite import Sqlite
from edmunds.database.drivers.sqlitememory import SqliteMemory
from threading import Lock


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
        :rtype:     flask_sqlalchemy.SQLAlchemy
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
        self._load_lock_sql_alchemy = Lock()

    def _load(self):
        """
        Load all the instances
        """

        if self._instances is not None:
            return
        with self._load_lock_sql_alchemy:
            if self._instances is not None:
                return

            self._init_sql_alchemy()

            return super(DatabaseManager, self)._load()

    def _init_sql_alchemy(self):
        """
        Init sql alchemy
        :return:    void
        """

        database_uri = None
        binds = {}

        for instances_config_item in self._instances_config:
            instance_database_uri = None

            if instances_config_item['driver'] == MySql \
                    or instances_config_item['driver'] == PostgreSql:

                if 'user' not in instances_config_item \
                        or 'pass' not in instances_config_item \
                        or 'host' not in instances_config_item \
                        or 'database' not in instances_config_item:
                    raise RuntimeError("Database-driver '%s' is missing some configuration ('user', 'pass', 'host' and 'database' are required)." % instances_config_item['name'])

                driver = instances_config_item['driver'].__name__.lower()
                database_user = instances_config_item['user']
                database_pass = instances_config_item['pass']
                database_host = instances_config_item['host']
                database_database = instances_config_item['database']
                if 'port' in instances_config_item:
                    database_port = instances_config_item['port']
                elif instances_config_item['driver'] == PostgreSql:
                    database_port = 5432
                else:
                    database_port = 3306

                instance_database_uri = '%s://%s:%s@%s:%s/%s' % (driver, database_user, database_pass, database_host, database_port, database_database)

            elif instances_config_item['driver'] == Sqlite:
                if 'file' not in instances_config_item:
                    raise RuntimeError("Database-driver '%s' is missing some configuration ('file' is required)." % instances_config_item['name'])

                sqlite_file = instances_config_item['file']
                sqlite_storage_name = instances_config_item['storage'] if 'storage' in instances_config_item else None
                sqlite_path = self._app.fs(name=sqlite_storage_name).path(sqlite_file)

                instance_database_uri = 'sqlite:///%s' % sqlite_path

            elif instances_config_item['driver'] == SqliteMemory:
                if not self._app.debug and not self._app.testing:
                    raise RuntimeError("SqliteMemory should not be used in non-debug and non-testing environment!")
                instance_database_uri = 'sqlite://'

            if database_uri is None:
                database_uri = instance_database_uri
            else:
                binds[instances_config_item['name']] = instance_database_uri

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

        bind = config['name']
        if self._instances_config and self._instances_config[0]['name'] == config['name']:
            bind = None
        return db.get_engine(bind=bind)

    def _create_postgre_sql(self, config):
        """
        Create PostgreSQL
        :param config:  The config
        :return:        SQLAlchemy Engine
        :rtype:         sqlalchemy.engine.base.Engine
        """

        db = DatabaseManager.get_sql_alchemy_instance()

        bind = config['name']
        if self._instances_config and self._instances_config[0]['name'] == config['name']:
            bind = None
        return db.get_engine(bind=bind)

    def _create_sqlite(self, config):
        """
        Create SQLite
        :param config:  The config
        :return:        SQLAlchemy Engine
        :rtype:         sqlalchemy.engine.base.Engine
        """

        db = DatabaseManager.get_sql_alchemy_instance()

        bind = config['name']
        if self._instances_config and self._instances_config[0]['name'] == config['name']:
            bind = None
        return db.get_engine(bind=bind)

    def _create_sqlite_memory(self, config):
        """
        Create SQLite
        :param config:  The config
        :return:        SQLAlchemy Engine
        :rtype:         sqlalchemy.engine.base.Engine
        """

        db = DatabaseManager.get_sql_alchemy_instance()

        bind = config['name']
        if self._instances_config and self._instances_config[0]['name'] == config['name']:
            bind = None
        return db.get_engine(bind=bind)
