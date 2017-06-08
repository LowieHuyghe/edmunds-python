
# Migrations

Database migrations are built in in Edmunds using Flask-Migrate.


## Usage

Edmunds has already integrated the db-command in the application's manager.
You can use it as described in the Flask-Migrate-documentation:
```bash
python manage.py db --help
```

Usage of Flask-Migrate documentation:
* [Flask-Migrate](https://flask-migrate.readthedocs.io)

### Models

Models are used by Flask-Migrate to describe your database structure.
Add your model to `app.models` so they are picked up by the
migration-service:
```python
# app/models/mymodel.py

from edmunds.database.model import db, Model

class MyModel(Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(128))
```

> Note: If you want to customize the package where the migrate-service looks
> for models, you can override it in your config:
> ```python
> APP = {
>     'database': {
>         # ...
>         'models': {
>             'my/location/to/the/models',
>             'second/location/to/other/models',
>         },
>     },
> }
> ```
> The described paths are relative to the root-folder of your application.
