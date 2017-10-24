
from edmunds.database.model import Column, Integer, String
from flask_security import RoleMixin as FlaskSecurityRoleMixin


class RoleMixin(FlaskSecurityRoleMixin, object):
    """
    Role Mixin
    """

    # __tablename__ = 'role'
    # __bind_key__ = 'users'

    id = Column(Integer, primary_key=True)
    name = Column(String(50), unique=True)
    description = Column(String(255))

    def __repr__(self):
        return '<Role id="%s"/>' % self.id
