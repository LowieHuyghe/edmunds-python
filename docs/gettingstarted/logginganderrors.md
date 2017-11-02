
# Logging and Errors

Logging and error-handling are built in and ready when you are.


## Logging

Edmunds comes with logging built in. You can activate it in your settings:
```python
from edmunds.log.drivers.file import File
from edmunds.log.drivers.stream import Stream
from edmunds.log.drivers.syslog import SysLog
from edmunds.log.drivers.timedfile import TimedFile
from edmunds.log.drivers.googleappengine import GoogleAppEngine
from logging.handlers import SysLogHandler, SYSLOG_UDP_PORT
from logging import WARNING
from socket import SOCK_DGRAM
import sys

APP = {
    'logging':
    {
        'enabled': True,
        'instances':
        [
            {
                'name': 'file',
                'driver': File,
                # 'directory': 'logs', 		# Optional, default: 'logs'
                # 'prefix': 'Myapp.', 		# Optional, default: ''
                # 'max_bytes': 0, 			# Optional, default: 0
                # 'backup_count': 0, 		# Optional, default: 0
                # 'level': WARNING, 		# Optional, default: WARNING
                # 'format': '%(message)s', 	# Optional, default: '[%(asctime)s] %(levelname)s: %(message)s [in %(pathname)s:%(lineno)d]'
            },
            {
                'name': 'timedfile',
                'driver': TimedFile,
                # 'directory': 'logs', 					# Optional, default: 'logs'
                # 'prefix': 'Myapp.', 					# Optional, default: ''
                # 'when': 'H', 							# Optional, default: 'D'
                # 'interval': 12, 						# Optional, default: 1
                # 'backup_count': 0, 					# Optional, default: 0
                # 'level': WARNING, 					# Optional, default: WARNING
                # 'format': '%(message)s', 				# Optional, default: '[%(asctime)s] %(levelname)s: %(message)s [in %(pathname)s:%(lineno)d]'
            },
            {
                'name': 'syslog',
                'driver': SysLog,
                # 'address': '/dev/log', 				# Optional, default: ('localhost', SYSLOG_UDP_PORT)
                # 'facility': SysLogHandler.LOG_USER, 	# Optional, default: SysLogHandler.LOG_USER
                # 'socktype': SOCK_DGRAM, 				# Optional, default: SOCK_DGRAM
                # 'level': WARNING, 					# Optional, default: WARNING
                # 'format': '%(message)s', 				# Optional, default: '[%(asctime)s] %(levelname)s: %(message)s [in %(pathname)s:%(lineno)d]'
            },
            {
                'name': 'stream',
                'driver': Stream,
                # 'stream': sys.stderr, 				# Optional, default: sys.stderr
                # 'level': WARNING, 					# Optional, default: WARNING
                # 'format': '%(message)s', 				# Optional, default: '[%(asctime)s] %(levelname)s: %(message)s [in %(pathname)s:%(lineno)d]'
            },
            {
                'name': 'googleappengine',
                'driver': GoogleAppEngine,
                # 'level': WARNING, 					# Optional, default: WARNING
                # 'format': '%(message)s', 				# Optional, default: '%(levelname)-8s %(asctime)s %(filename)s:%(lineno)s] %(message)s'
            },
        ],
    },
}
```
The instances will all be used for logging, so you can have multiple at once.

The available drivers are:

- **File**: Print logs to file which can be separated by size.
- **TimedFile**: Print logs to file which can be separated by time-interval.
- **SysLog**: Print logs to syslog.
- **Stream**: Pring logs to given stream.
- **GoogleAppEngine**: Pring logs to the Google App Engine stream when running in Google App Engine runtime.


## Errors

Even the best programming logic and tests can't always foresee every
possible scenario where errors can occure. You can report and render
these exceptions by registering your own exception-handler.

If logging is enabled, errors that pass through the handler will
automatically be logged to your provider logging-services.


### Define

Define your Handler like so:
```python
from edmunds.exceptions.handler import Handler as EdmundsHandler

class Handler(EdmundsHandler):
    """
    Exception handler
    """

    def report(self, exception):
        """
        Report the exception
        :param exception:   The exception
        :type  exception:   Exception
        """
        if super(Handler, self).report(exception):
            # Additional reporting
            pass

    def render(self, exception):
        """
        Render the exception
        :param exception:   The exception
        :type  exception:   Exception
        :return:            The response
        """
        return super(Handler, self).render(exception)
```

**Important!**: The `report`-function of the edmunds-super-class will log
the error to `self.app.logger`. You defined loggers will by default pick up
caught exceptions as described above.

### Register

Register the Handler for usage in `config/app.py`:
```python
from app.exceptions.handler import Handler

APP = {
    'exceptions':
    {
        'handler': Handler,
    },
}
```
This way the application knows to use your handler in case of an exception.
