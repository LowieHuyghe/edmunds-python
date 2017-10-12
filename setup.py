
# Imports
import os
from setup.easiersetup import EasierSetup


# Construct and setup
EasierSetup(
    os.path.abspath(os.path.dirname(__file__)),
    'setup.config.ini'
).setup()
