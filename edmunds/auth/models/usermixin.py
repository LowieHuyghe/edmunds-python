
from edmunds.database.db import db
from flask_security import UserMixin as FlaskSecurityUserMixin


class UserMixin(FlaskSecurityUserMixin, object):
    """
    User Mixin
    """

    # __tablename__ = 'user'
    # __bind_key__ = 'users_database'

    id = db.Column(db.Integer, nullable=False, primary_key=True)
    email = db.Column(db.String(255), nullable=False, unique=True)
    password = db.Column(db.String(255))
    active = db.Column(db.Boolean())
    confirmed_at = db.Column(db.DateTime())
    last_login_at = db.Column(db.DateTime())
    current_login_at = db.Column(db.DateTime())
    # Why 45 characters for IP Address ?
    # See http://stackoverflow.com/questions/166132/maximum-length-of-the-textual-representation-of-an-ipv6-address/166157#166157
    last_login_ip = db.Column(db.String(45))
    current_login_ip = db.Column(db.String(45))
    login_count = db.Column(db.Integer)

    def __repr__(self):
        return '<User id="%s" email="%s"/>' % (self.id, self.email)
