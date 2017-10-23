
from tests.testcase import TestCase
from edmunds.database.model import Model
from edmunds.auth.models.user import User
from flask_security import UserMixin


class TestUser(TestCase):
    """
    Test user
    """

    def test_user(self):
        """
        Test user model
        :return:    void
        """

        id = self.rand_str(20)
        email = self.rand_str(20)
        password = self.rand_str(20)
        active = self.rand_str(20)
        confirmed_at = self.rand_str(20)
        last_login_at = self.rand_str(20)
        current_login_at = self.rand_str(20)
        last_login_ip = self.rand_str(20)
        current_login_ip = self.rand_str(20)
        login_count = self.rand_str(20)

        user = User()
        user.id = id
        user.email = email
        user.password = password
        user.active = active
        user.confirmed_at = confirmed_at
        user.last_login_at = last_login_at
        user.current_login_at = current_login_at
        user.last_login_ip = last_login_ip
        user.current_login_ip = current_login_ip
        user.login_count = login_count

        self.assert_is_instance(user, Model)
        self.assert_is_instance(user, UserMixin)

        self.assert_equal_deep(id, user.id)
        self.assert_equal_deep(email, user.email)
        self.assert_equal_deep(password, user.password)
        self.assert_equal_deep(active, user.active)
        self.assert_equal_deep(confirmed_at, user.confirmed_at)
        self.assert_equal_deep(last_login_at, user.last_login_at)
        self.assert_equal_deep(current_login_at, user.current_login_at)
        self.assert_equal_deep(last_login_ip, user.last_login_ip)
        self.assert_equal_deep(current_login_ip, user.current_login_ip)
        self.assert_equal_deep(login_count, user.login_count)

        self.assert_in(id, '%s' % user)

    def test_user_columns(self):
        """
        Test user columns
        :return:    void
        """

        self.assert_equal(10, len(User.__table__.columns))
        self.assert_in('id', User.__table__.columns)
        self.assert_in('email', User.__table__.columns)
        self.assert_in('password', User.__table__.columns)
        self.assert_in('active', User.__table__.columns)
        self.assert_in('confirmed_at', User.__table__.columns)
        self.assert_in('last_login_at', User.__table__.columns)
        self.assert_in('current_login_at', User.__table__.columns)
        self.assert_in('last_login_ip', User.__table__.columns)
        self.assert_in('current_login_ip', User.__table__.columns)
        self.assert_in('login_count', User.__table__.columns)
