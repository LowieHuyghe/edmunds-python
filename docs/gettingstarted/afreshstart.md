
# A Fresh Start

### 1. Create a new project

Let's first get an Edmunds Instance to kick-start your
project. Download it from:
> [https://github.com/LowieHuyghe/edmunds-instance/tarball/master](https://github.com/LowieHuyghe/edmunds-instance/tarball/master)  
> (Tar-file because zip does not preserve file-permissions on Unix-filesystem.)

Un-tar it, move it and rename it after your project.

### 2. Setup a virtual environment

Now setup a virtual environment for your project. If you
need some help with that, you can take a look at
[The Hitchhiker's guide to Python](http://docs.python-guide.org/en/latest/dev/virtualenvs/).

Now activate your project's virtual environment.

### 3. Install the dependencies

First install some required setup packages:
```bash
pip install setuptools_scm
``` 

Now install all other dependencies:
```bash
pip install -r requirements.txt
```

### 4. Some configuration

Next we make a copy of the `.env.example.py`-file and rename it to `.env.py`.
Change the configuration in the file where needed.

> **Important!**: Don't forget to personalise `SECRET_KEY` and `SECURITY_PASSWORD_SALT`
for production!

### 5. Take it for a spin!

Let's take it for a spin and run the application:
```bash
python manage.py run
```

### 6. Google App Engine (optional)

If you want to develop for and run in Google App Engine
you'll first need to install the [App Engine SDK](https://cloud.google.com/appengine/docs/standard/python/download).

Google App Engine requires you to install dependencies into a directory. More
specifically the lib-directory. Unfortunately pip does not work very
well when using a target directory to install dependencies. To fix this
Edmunds is equipped with a command that combines all dependencies and eggs
of the given environment into one directory!

First make sure your project's virtual environment is activated.

Secondly run the following command to install all dependencies into the
lib-directory:
```bash
python manage.py pip-install-into -t lib -p pip
```

Now start the development server and you are good to go:
```bash
dev_appserver.py app.yaml
```
