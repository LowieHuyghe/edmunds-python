
from edmunds.database.model import Column, Integer, String, Boolean, DateTime
from flask_security import UserMixin as FlaskSecurityUserMixin


class UserMixin(FlaskSecurityUserMixin, object):
    """
    User Mixin
    """

    # __tablename__ = 'user'
    # __bind_key__ = 'users_database'

    id = Column(Integer, primary_key=True)
    email = Column(String(255), unique=True)
    password = Column(String(255))
    active = Column(Boolean())
    confirmed_at = Column(DateTime())
    last_login_at = Column(DateTime())
    current_login_at = Column(DateTime())
    last_login_ip = Column(String(255))
    current_login_ip = Column(String(255))
    login_count = Column(Integer)

    def __repr__(self):
        return '<User id="%s"/>' % self.id
