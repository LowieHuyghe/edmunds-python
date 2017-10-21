
from tests.testcase import TestCase
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

        role = Role(
            id,
            name=name,
            description=description
        )
        self.assert_is_instance(role, RoleMixin)

        self.assert_equal_deep(id, role.id)
        self.assert_equal_deep(name, role.name)
        self.assert_equal_deep(description, role.description)

        self.assert_in(id, '%s' % role)
