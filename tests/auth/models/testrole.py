
from tests.testcase import TestCase
from edmunds.database.model import Model
from edmunds.auth.models.role import Role
from flask_security import RoleMixin


class TestRole(TestCase):
    """
    Test role
    """

    def test_role(self):
        """
        Test role model
        :return:    void
        """

        id = self.rand_str(20)
        name = self.rand_str(20)
        description = self.rand_str(20)

        role = Role()
        role.id = id
        role.name = name
        role.description = description

        self.assert_is_instance(role, Model)
        self.assert_is_instance(role, RoleMixin)

        self.assert_equal_deep(id, role.id)
        self.assert_equal_deep(name, role.name)
        self.assert_equal_deep(description, role.description)

        self.assert_in(id, '%s' % role)

    def test_role_columns(self):
        """
        Test role columns
        :return:    void
        """

        self.assert_equal(3, len(Role.__table__.columns))
        self.assert_in('id', Role.__table__.columns)
        self.assert_in('name', Role.__table__.columns)
        self.assert_in('description', Role.__table__.columns)
