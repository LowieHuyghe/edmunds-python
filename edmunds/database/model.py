
from edmunds.globals import current_app
import sqlalchemy.orm


mapper = sqlalchemy.orm.mapper
relationship = sqlalchemy.orm.relationship
backref = sqlalchemy.orm.backref


class Model(object):
    """
    Model base class
    """

    __table__ = None

    @classmethod
    def session(cls, name=None, no_instance_error=False):
        """
        Session
        :param name:                Name of the bind 
        :param no_instance_error:   No error when name does not exist 
        :return:                    sqlalchemy.orm.scoping.scoped_session
        """

        if name is None \
                and cls.__table__ is not None \
                and hasattr(cls.__table__, 'info') \
                and cls.__table__.info \
                and 'bind_key' in cls.__table__.info \
                and cls.__table__.info['bind_key']:
            name = cls.__table__.info['bind_key']

        return current_app.database_session(name=name, no_instance_error=no_instance_error)

    @classmethod
    def query(cls, name=None, no_instance_error=False):
        """
        Query
        :param name:                Name of the bind 
        :param no_instance_error:   No error when name does not exist
        :return:                    sqlalchemy.orm.scoping.query
        """

        Session = cls.session(name=name, no_instance_error=no_instance_error)
        if Session is None:
            return None

        return Session.query(cls)
