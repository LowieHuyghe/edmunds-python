
# Storage

File-storage is a basic, necessary thing for every application.
And in Edmunds it's highly customizable!


## Settings

You can set your storage preferences in the settings:
```python
from edmunds.storage.drivers.file import File
from edmunds.storage.drivers.googlecloudstorage import GoogleCloudStorage

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

The available drivers are:

- **File**: Store files using the default file-system.
- **GoogleCloudStorage**: Store files using Google Cloud Storage.


## Usage

You can use the handler like so:
```python
# Fetch the default driver, or by name
driver = app.fs()
driver = app.fs('googlecloudstorage')

# Path
# This function is used when processing the input of the other functions below
absolute_path = app.fs().path('file.txt')  # /abs_path_to_storage/files/{prefix}file.txt
absolute_path = app.fs().path('/file.txt')  # /abs_path_to_storage/{prefix}file.txt
absolute_path = app.fs().path('directory/')  # /abs_path_to_storage/files/directory/
absolute_path = app.fs().path('/directory/')  # /abs_path_to_storage/directory/
absolute_path = app.fs().path(None)  # /abs_path_to_storage/files/
absolute_path = app.fs().path('/')  # /abs_path_to_storage/
# Each function can set the prefix for that call:
absolute_path = app.fs().path('file.txt', prefix='')  # /abs_path_to_storage/files/file.txt

# Write stream
write_stream = app.fs().write_stream('file.txt')

# Read stream
write_stream = app.fs().read_stream('file.txt', prefix='')

# Copy file
success = app.fs().copy('file.txt', 'file.txt.bak')

# Remove file
success = app.fs().delete('file.txt', prefix='')

# Exists?
does_not_exist = app.fs().exists('file.txt')
```