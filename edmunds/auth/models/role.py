
from edmunds.database.model import Model, Column, Integer, String
from flask_security import RoleMixin


class Role(Model, RoleMixin):
    """
    Role Model
    """

    __tablename__ = 'role'
    # __bind_key__ = 'users'

    id = Column(Integer, primary_key=True)
    name = Column(String(50), unique=True)
    description = Column(String(255))

    def __repr__(self):
        return '<Role id="%s"/>' % self.id
