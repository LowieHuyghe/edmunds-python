
# Storage

File-storage is a basic, necessary thing for every application.
And in Edmunds it's highly customizable!


## Settings

You can set your storage preferences in the settings:
```python
from Edmunds.Storage.Drivers.File import File
from Edmunds.Storage.Drivers.GoogleCloudStorage import GoogleCloudStorage

APP = {
    'storage':
    {
        'instances':
        [
            {
                'name': 'file',
                'driver': File,
                # 'directory': '/storage', 	# Optional, default: '/storage'
                # 'files_path': 'files', 	# Optional, default: 'files'
                # 'prefix': 'Myapp.', 		# Optional, default: ''
            },
            {
                'name': 'googlecloudstorage',
                'driver': GoogleCloudStorage,
                # 'bucket': 'mybucket', 	# Optional, default: default bucket
                # 'directory': '/storage', 	# Optional, default: '/storage'
                # 'files_path': 'files', 	# Optional, default: 'files'
                # 'prefix': 'Myapp.', 		# Optional, default: ''
            },
        ],
    },
}
```
The instances can be used for storage, so you can have multiple at once.
The first one will be used by default.

If `directory` starts with a separator it will be handled differently:
- `/mystorage` => `/root_path/mystorage`
- `mystorage` => `/root_path/storage/mystorage`

The available drivers are:
- **File**: Store files using the default file-system.
- **GoogleCloudStorage**: Store files using Google Cloud Storage.


## Usage

You can use the handler like so:
```python
# Fetch the default driver, or by name
driver = app.fs()
driver = app.fs('googlecloudstorage')

# Write stream
write_stream = app.fs().write_stream('file.txt')

# Read stream
write_stream = app.fs().read_stream('file.txt')

# Copy file
success = app.fs().copy('file.txt', 'file.txt.bak')

# Remove file
success = app.fs().delete('file.txt')

# Exists?
does_not_exist = app.fs().exists('file.txt')

# Path
absolute_path = app.fs().path('file.txt')  # /abs_path_to_storage/files/file.txt
absolute_path = app.fs().path('/file.txt')  # /abs_path_to_storage/file.txt
absolute_path = app.fs().path('directory/')  # /abs_path_to_storage/files/directory/
absolute_path = app.fs().path('/directory/')  # /abs_path_to_storage/directory/
absolute_path = app.fs().path(None)  # /abs_path_to_storage/files/
absolute_path = app.fs().path('/')  # /abs_path_to_storage/
```