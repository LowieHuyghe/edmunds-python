
from edmunds.database.model import Model, Column, Integer, String, Boolean, DateTime
from flask_security import UserMixin


class User(Model, UserMixin):
    """
    User Model
    """

    __tablename__ = 'user'
    # __bind_key__ = 'users'

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
