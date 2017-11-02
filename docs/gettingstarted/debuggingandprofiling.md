
# Debugging and Profiling

When all hell breaks loose or your application is underperforming,
debugging and profiling is the answer.


## Debugging

Debugging your application can be done in several ways. Using your
IDE, `pdb`, `pudb`,... There are many options, but here `pdb` and
`pudb` will be explained.

### Pdb

[Pdb, the python debugger](https://docs.python.org/2/library/pdb.html),
is a minimal debugger that comes straight out of the box in python.
It runs in the terminal and has a minimal interface.

#### Start pdb

```bash
python -m pdb manage.py run
```

#### Debug with pdb

Add breakpoints like so:
```python
from pdb import set_trace
set_trace()
```

More debug-options can be found in the [documentation](https://docs.python.org/2/library/pdb.html).

### Pudb

[Pudb](https://pypi.python.org/pypi/pudb) is a more interactive debugger.
It runs in the terminal and has a nice interface which displays the code,
stack, variables,...

#### Install pudb

1. Download the `tar.gz` from [PyPi](https://pypi.python.org/pypi/pudb)
2. Run `./setup.py install`

#### Start pudb

```bash
pudb manage.py run
```

#### Debug with pudb

```python
from pudb import set_trace
set_trace()
```

More debug-options can be found in the [documentation](https://pypi.python.org/pypi/pudb).


## Profiling

Edmunds comes with profiling built in. You can activate it in your settings:
```python
from edmunds.profiler.drivers.callgraph import CallGraph
from edmunds.profiler.drivers.stream import Stream
from edmunds.profiler.drivers.blackfireio import BlackfireIo
import sys

APP = {
    'debug': True,
    'profiler':
    {
        'enabled': True,
        'instances':
        [
            {
                'name': 'stream',
                'driver': Stream,
                # 'stream': sys.stdout, 	# Optional, default: sys.stdout
                # 'sort_by': ('calls'), 	# Optional, default: ('time', 'calls')
                # 'restrictions': (), 		# Optional, default: ()
            },
            {
                'name': 'callgraph',
                'driver': CallGraph,
                # 'directory': 'profs', 	# Optional, default: 'profs'
                # 'prefix': 'Myapp.', 		# Optional, default: ''
            },
            {
                'name': 'blackfireio',
                'driver': BlackfireIo,
                # 'directory': 'profs', 	# Optional, default: 'profs'
                # 'prefix': 'Myapp.', 		# Optional, default: ''
            },
        ],
    },
}
```
The instances will all be used for profiling, so you can have multiple at once.

The available drivers are:

- **Stream**: Print output in the given stream.
- **CallGraph**: Outputs in CallGraph-format to open in a viewer.
- **BlackfireIo**: Output in BlackfireIo-format to upload to [Blackfire.io](https://blackfire.io/).
