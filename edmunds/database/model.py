
from edmunds.application import current_app
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
    def session(cls, name=None):
        """
        Session
        :param name:    Name of the bind 
        :return:        sqlalchemy.orm.scoping.scoped_session
        """

        if name is None \
                and cls.__table__ is not None \
                and hasattr(cls.__table__, 'info') \
                and cls.__table__.info \
                and 'bind_key' in cls.__table__.info \
                and cls.__table__.info['bind_key']:
            name = cls.__table__.info['bind_key']

        return current_app.database_session(name=name)

    @classmethod
    def query(cls, name=None):
        """
        Query
        :param name:    Name of the bind 
        :return:        sqlalchemy.orm.scoping.query
        """

        Session = cls.session(name=name)
        return Session.query(cls)
