# Python Package Easier Setup Script

An easier python setup script. Keep your `setup.py` organized with a simple ini-config-file.

Example: [setup.config.ini](https://github.com/LowieHuyghe/python-package-easier-setup-script/blob/master/setup.config.example.ini)


## Installation

1. `cd` into your project directory
2. Add the project as a submodule:

 ```bash
git submodule add git@github.com:LowieHuyghe/python-package-easier-setup-script.git setup
```
3. Copy the example-files to your project

 ```bash
cp ./setup/setup.example.py ./setup.py
cp ./setup/setup.config.example.ini ./setup.config.ini
```
4. Change `setup.config.ini` to your likings:
  * Setup-kwargs that expect plain string:

 ```ini
[Section]
key: plain text value
key: file://README.md  # Read from file
```
  * Setup-kwargs that expect lists:

 ```ini
[Section]
key: plain, text, comma, separated
key: file://requirements.txt  # Read from file. Each line is an item in the list.
```
5. Debug the generated setup kwargs with:

 ```bash
python setup.py --debug
```


## Usage

You can use the setup.py as you normally would:
```bash
python setup.py test
```
