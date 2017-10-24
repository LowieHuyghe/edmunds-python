
from tests.testcase import TestCase
from edmunds.auth.models.rolemixin import RoleMixin
from flask_security import RoleMixin as FlaskSecurityRoleMixin


class TestRoleMixin(TestCase):
    """
    Test role mixin
    """

    def test_role(self):
        """
        Test role model
        :return:    void
        """

        id = self.rand_str(20)
        name = self.rand_str(20)
        description = self.rand_str(20)

        role = RoleMixin()
        role.id = id
        role.name = name
        role.description = description

        self.assert_is_instance(role, FlaskSecurityRoleMixin)

        self.assert_equal_deep(id, role.id)
        self.assert_equal_deep(name, role.name)
        self.assert_equal_deep(description, role.description)

        self.assert_in(id, '%s' % role)
