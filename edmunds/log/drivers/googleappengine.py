
from google.appengine.api.app_logging import AppLogsHandler
from logging import StreamHandler, Formatter, WARNING


class GoogleAppEngine(AppLogsHandler):
    """
    Google App Engine Driver
    """

    def __init__(self, app, level=WARNING):
        """
        Init google app engine logging handler
        :param app:     The application
        :type app:      edmunds.application.Application
        :param level:   Level of reporting
        :type level:    int
        """
        super(GoogleAppEngine, self).__init__(level=level)

        self.development_handler = None
        if app.is_gae_development():
            handler = StreamHandler()
            handler.setLevel(self.level)
            handler.setFormatter(Formatter('%(levelname)-8s %(asctime)s %(filename)s:%(lineno)s] %(message)s'))
            self.development_handler = handler

    def emit(self, *args, **kwargs):
        """
        Emit event
        :param args:    Arguments
        :param kwargs:  Kwarguments
        :return:        void
        """
        super(GoogleAppEngine, self).emit(*args, **kwargs)

        if self.development_handler is not None:
            self.development_handler.emit(*args, **kwargs)
