
from tests.testcase import TestCase
from edmunds.validation.validator import Validator
from wtforms import Form, BooleanField, StringField, PasswordField, validators
from werkzeug.datastructures import MultiDict


class TestValidator(TestCase):
    """
    Test the validator
    """

    def test_validates(self):
        """
        Test validates
        :return:    void
        """

        data = [
            (False, {}),
            (False, {'email': 'test@example.com', 'password': 'pass', 'confirm': '', 'accept_tos': True}),
            (False, {'email': 'test@example.com', 'password': 'pass', 'confirm': 'pass'}),
            (False, {'email': 'test@example.com', 'password': 'pass', 'confirm': 'passs', 'accept_tos': True}),
            (True, {'email': 'test@example.com', 'password': 'pass', 'confirm': 'pass', 'accept_tos': True}),
        ]

        for expected, given_data in data:
            validator = MyValidator(MultiDict(given_data))

            self.assert_is_instance(validator, Form)
            self.assert_is_none(validator.validates)
            self.assert_equal(expected, validator.validate())
            self.assert_equal(expected, validator.validates)


class MyValidator(Validator):
    email = StringField('Email Address', [validators.Length(min=6, max=35)])
    password = PasswordField('New Password', [
        validators.DataRequired(),
        validators.EqualTo('confirm', message='Passwords must match')
    ])
    confirm = PasswordField('Repeat Password')
    accept_tos = BooleanField('I accept the TOS', [validators.DataRequired()])
