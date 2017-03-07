
# Update submodules
from subprocess import Popen
Popen('git submodule update --init --recursive', shell=True).wait()


# Imports
import os
from setup.setup import Setup


# Construct and setup
Setup(
    os.path.abspath(os.path.dirname(__file__)),
    'setup.classifiers.txt',
    'setup.config.ini'
).setup()
