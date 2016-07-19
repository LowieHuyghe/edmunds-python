<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Default Filesystem Disk
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default filesystem disk that should be used
	| by the framework. A "local" driver, as well as a variety of cloud
	| based drivers are available for your choosing. Just store away!
	|
	| Supported: "local", "ftp", "s3", "rackspace", "gcs", "gcsstream"
	|
	*/

	'default' => env('FILESYSTEM_DRIVER', 'local'),

	/*
	|--------------------------------------------------------------------------
	| Default Cloud Filesystem Disk
	|--------------------------------------------------------------------------
	|
	| Many applications store files both locally and in the cloud. For this
	| reason, you may specify a default "cloud" driver here. This driver
	| will be bound as the Cloud disk implementation in the container.
	|
	*/

	'cloud' => env('FILESYSTEM_CLOUD', 's3'),

	/*
	|--------------------------------------------------------------------------
	| Filesystem Disks
	|--------------------------------------------------------------------------
	|
	| Here you may configure as many filesystem "disks" as you wish, and you
	| may even configure multiple disks of the same driver. Defaults have
	| been setup for each driver as an example of the required options.
	|
	*/

	'disks' => [

		'local' => [
			'driver' => 'local',
			'root' => storage_path('app'),
		],

		'public' => [
			'driver' => 'local',
			'root' => storage_path('app/public'),
			'visibility' => 'public',
		],

		's3' => [
			'driver' => 's3',
			'key' => env('S3_KEY', 'your-key'),
			'secret' => env('S3_SECRET', 'your-secret'),
			'region' => env('S3_REGION', 'your-region'),
			'bucket' => env('S3_BUCKET', 'your-bucket'),
		],

		'gcs' => [
			'driver' => 'gcs',
			'key' => env('GCS_KEY', 'your-key'),
			'secret' => env('GCS_SECRET', 'your-secret'),
			'bucket' => env('GCS_BUCKET', 'your-bucket'),
		],

		'gcsstream' => [
			'driver' => 'gcsstream',
			'bucket' => env('GCS_STREAM_BUCKET', 'your-bucket'),
		],

	],

];