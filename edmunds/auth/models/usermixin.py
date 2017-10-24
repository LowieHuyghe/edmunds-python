
from edmunds.database.model import db
from flask_security import UserMixin as FlaskSecurityUserMixin


class UserMixin(FlaskSecurityUserMixin, object):
    """
    User Mixin
    """

    # __tablename__ = 'user'
    # __bind_key__ = 'users_database'

    id = db.Column(db.Integer, primary_key=True)
    email = db.Column(db.String(255), unique=True)
    password = db.Column(db.String(255))
    active = db.Column(db.Boolean())
    confirmed_at = db.Column(db.DateTime())
    last_login_at = db.Column(db.DateTime())
    current_login_at = db.Column(db.DateTime())
    last_login_ip = db.Column(db.String(255))
    current_login_ip = db.Column(db.String(255))
    login_count = db.Column(db.Integer)

    def __repr__(self):
        return '<User id="%s"/>' % self.id
