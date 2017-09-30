
from edmunds.database.model import Model
from flask_security import RoleMixin


class Role(Model, RoleMixin):
    """
    Role Model
    """

    def __init__(self, id, name=None, description=None):
        """
        Constructor
        :param id:          The id
        :param name:        The name
        :param description: The description
        """
        self.id = id
        self.name = name
        self.description = description

    def __repr__(self):
        return '<Role id="%s"/>' % self.id
