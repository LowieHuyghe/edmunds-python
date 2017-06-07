
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

Usage of those classes is described in the Flask-Script documentation:
* [Flask-Script](https://flask-script.readthedocs.io)

### Register custom command

`manage.py` runs by default the Manager located in `app.console`.  
You can register your custom commands (located in `app.console.commands`)
in the manager as described by the Flask-Script documentation:
```python
# ...
from app.console.commands.helloworldcommand import HelloWorldCommand

class Manager(EdmundsManager):
    # ...
    def add_default_commands(self):
        # ...
        self.add_command('helloworld', HelloWorldCommand())
```
