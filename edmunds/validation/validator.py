
from wtforms import Form


class Validator(Form):

    def __init__(self, *args, **kwargs):
        """
        :param fields:
            A dict or sequence of 2-tuples of partially-constructed fields.
        :param prefix:
            If provided, all fields will have their name prefixed with the
            value.
        :param meta:
            A meta instance which is used for configuration and customization
            of WTForms behaviors.
        """

        super(Validator, self).__init__(*args, **kwargs)

        self.validates = None

    def validate(self, *args, **kwargs):
        """
        Validates the form by calling `validate` on each field.

        :param extra_validators:
            If provided, is a dict mapping field names to a sequence of
            callables which will be passed as extra validators to the field's
            `validate` method.

        Returns `True` if no errors occur.
        """

        self.validates = None
        self.validates = super(Validator, self).validate(*args, **kwargs)
        return self.validates
