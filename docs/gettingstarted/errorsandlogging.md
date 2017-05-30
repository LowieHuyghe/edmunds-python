
# Errors and Logging

Error-handling and logging are built in and ready when you are.


## Errors

Even the best programming logic and tests can't always foresee every possible scenario where errors can occure. You can report and render these exceptions by registering your own exception-handler.


### Define

Define your Handler like so:
```python
from Edmunds.Exceptions.Handler import Handler as EdmundsHandler

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
            pass  # Report

    def render(self, exception):
        """
        Render the exception
        :param exception:   The exception
        :type  exception:   Exception
        :return:            The response
        """
        return super(Handler, self).render(exception)
```


### Register

Register the Handler for usage in `config/app.py`:
```python
'exceptions':
{
    'handler': Handler,
},
```
This way the application knows to use your handler in case of an exception.


## Logging

Edmunds comes with logging built in. You can activate it in your settings:
```python
from Edmunds.Log.Drivers.File import File
from Edmunds.Log.Drivers.Stream import Stream
from Edmunds.Log.Drivers.SysLog import SysLog
from Edmunds.Log.Drivers.TimedFile import TimedFile
from Edmunds.Log.Drivers.GoogleAppEngine import GoogleAppEngine
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