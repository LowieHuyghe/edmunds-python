
# Console

Console usage is built in in Edmunds and uses Flask-Script.


## Usage

Edmunds has a mapping which matches the Flask-Script objects:
```python
from edmunds.console.manager import Manager
# matches: from flask_script import Manager

from edmunds.console.command import Command
# matches: from flask_script import Command

from edmunds.console.command import Option
# matches: from flask_script import Option
```

Further usage of the manager is described in the Flask-Script documentation:
* [Flask-Script](https://flask-script.readthedocs.io)
