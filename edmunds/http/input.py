
from edmunds.globals import has_request_context
from werkzeug.datastructures import MultiDict


class Input(MultiDict):
    """
    The request input
    """

    def __init__(self, request):
        """
        Constructor
        :param request: Flask request
        """

        if has_request_context():
            data = request.args.copy()
            data.update(request.form)
            data.update(request.files)
        else:
            data = dict()

        super(Input, self).__init__(data)

    def validate(self, validator_class):
        """
        Validator class
        :param validator_class: Validator class 
        :return:                Validator
        """

        validator = validator_class(self)
        validator.validate()
        return validator
