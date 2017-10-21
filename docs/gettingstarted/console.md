
# Console

Console usage is built in in Edmunds and uses default Flask CLI.
Edmunds has wrappers to make usage easier and more organized.

Flask CLI uses click to register and use commands. More on this in the
[Flask CLI documentation](http://flask.pocoo.org/docs/0.12/cli/).

* The Manager (base: `edmunds.console.manager`) is responsible for
registering the commands.
* The Command (base: `edmunds.console.command`) is a wrapper that uses its
`run`-function as an entry-point for click. Option-decorators can be
defined there.

## Usage

### Construct command

Commands are by default located in the `app.console.commands`-module.
Add command decorators to the `run`-function like you normally would
wih click.

```python
from edmunds.console.command import Command
import click

class HelloWorldCommand(Command):
    """
    Prints Hello World!
    """
    @click.option('--what', default='World', help='Hello what?')
    def run(self, what):
        """
        Run the command
        :param what:    Hello what?
        """
        print('Hello %s!' % what)
```

### Register commands

The manager is by default located in the `app.console.manager`-module. 
You can register your custom commands (located in `app.console.commands`)
in the manager. This will wrap the `run`-function of the command in a
`click.command`-decorator so its available for console-usage.

```python
# ...
from edmunds.console.manager import Manager as EdmundsManager
from app.console.commands.helloworldcommand import HelloWorldCommand

class Manager(EdmundsManager):
    # ...
    def add_commands(self):
        # ...
        self.add_command('helloworld', HelloWorldCommand)
```

### Console usage

Console-usage of your application has been integrated in `manage.py`.
It's a wrapper for the `flask`-command which requires extra
environment-variables. By default the manager in `app.console.manager`
will be loaded. You can use `manage.py` by calling it in your
runtime-environment:

```bash
python manage.py --help
python manage.py helloworld
```
