
from edmunds.database.db import db
from flask_security import RoleMixin as FlaskSecurityRoleMixin


class RoleMixin(FlaskSecurityRoleMixin, object):
    """
    Role Mixin
    """

    # __tablename__ = 'role'
    # __bind_key__ = 'users'

    id = db.Column(db.Integer, nullable=False, primary_key=True)
    name = db.Column(db.String(50), nullable=False, unique=True)
    description = db.Column(db.String(255))

    def __repr__(self):
        return '<Role id="%s"/>' % self.id
