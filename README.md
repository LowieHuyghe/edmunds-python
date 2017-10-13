# Simplified setup.py for Python Packages

A Simplified setup.py for Python Packages! Keep your `setup.py` organized with a simple ini-config-file.

Example:
```ini-config-file
[General]
name: mypackage
version: scm
description: My package
long_description: file://README.md
url: https://github.com/LowieHuyghe/my-package
license: MIT
keywords: my, package
requirements: file://requirements.txt

[Author]
name: Lowie Huyghe
email: iam@lowiehuyghe.com

[Classifiers]
status: 3
programming_languages: Python 2, Python 2.7, Python 3, Python 3.3, Python 3.4, Python 3.5
audiences: Developers
topics: Build Tools
license: MIT

[Setup]
requirements: setuptools_scm

[Tests]
requirements: mock, nose
suite: nose.collector

[Packages]
include: mypackage*
exclude: tests*

[Commands]
my_command: ./bin/my-command.sh
my_command-description: This is my command
```


## Installation

1. Go to your project directory
2. Add this project as a squashed subtree:

 ```bash
git subtree add --prefix setup git@github.com:LowieHuyghe/python-simplified-setup-py.git master --squash

# To update:
git subtree pull --prefix setup git@github.com:LowieHuyghe/python-simplified-setup-py.git master --squash
```
3. Copy the example-files to your project

 ```bash
cp ./setup/setup.example.py ./setup.py
cp ./setup/setup.config.example.ini ./setup.config.ini
cp ./setup/MANIFEST.example.in ./MANIFEST.in
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
