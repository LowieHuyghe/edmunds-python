
from edmunds.database.model import Model
from flask_security import UserMixin


class User(Model, UserMixin):
    """
    User Model
    """

    def __init__(self, id, email=None, password=None, active=None, confirmed_at=None, last_login_at=None,
                 current_login_at=None, last_login_ip=None, current_login_ip=None, login_count=None):
        """
        Constructor
        :param id:                  The id
        :type id:                   int
        :param email:               The email
        :type email:                str
        :param password:            The hashed password
        :type password:             str
        :param active:              Active or inactive
        :type active:               bool
        :param confirmed_at:        Confirmed account at
        :type confirmed_at:         datetime
        :param last_login_at:       Last logged in at
        :type last_login_at:        datetime
        :param current_login_at:    Currently logged in at
        :type current_login_at:     datetime
        :param last_login_ip:       Last login ip
        :type last_login_ip:        str
        :param current_login_ip:    Current login ip
        :type current_login_ip:     str
        :param login_count:         Login count
        :type login_count:          int
        """
        self.id = id
        self.email = email
        self.password = password
        self.active = active
        self.confirmed_at = confirmed_at
        self.last_login_at = last_login_at
        self.current_login_at = current_login_at
        self.last_login_ip = last_login_ip
        self.current_login_ip = current_login_ip
        self.login_count = login_count

    def __repr__(self):
        return '<User id="%s"/>' % self.id
