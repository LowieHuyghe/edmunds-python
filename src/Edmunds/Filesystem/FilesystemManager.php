<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Filesystem;

use Aws\S3\S3Client;
use Edmunds\Filesystem\Adapter\GCSStreamAdapter;
use Illuminate\Filesystem\FilesystemManager as BaseFilesystemManager;
use Illuminate\Support\Arr;
use League\Flysystem\AwsS3v2\AwsS3Adapter;


/**
 * The filesystem manager for the filesystem drivers
 */
class FilesystemManager extends BaseFilesystemManager
{
	/**
	 * Create an instance of the Google Cloud Storage driver.
	 *
	 * @param  array  $config
	 * @return \Illuminate\Contracts\Filesystem\Cloud
	 */
	public function createGcsDriver(array $config)
	{
		$client = S3Client::factory(array(
			'key'    => $config['key'],
			'secret' => $config['secret'],
			'base_url' => 'https://storage.googleapis.com',
		));

		$prefix = isset($config['prefix']) ? $config['prefix'] : null;

		$options = isset($config['options']) ? $config['options'] : array();

		return $this->createFlysystem(new AwsS3Adapter(
			$client, $config['bucket'], $prefix, $options
		), $config);
	}

	/**
	 * Create an instance of the Google Cloud Storage stream driver.
	 *
	 * @param  array  $config
	 * @return \Illuminate\Contracts\Filesystem\Cloud
	 */
	public function createGcsstreamDriver(array $config)
	{
		$root = isset($config['root']) ? $config['root'] : '';
		$path = $this->getStoragePath($config['bucket'], $root);

		return $this->createFlysystem(new GCSStreamAdapter(
			$path
		), $config);
	}

	/**
	 * Get the storage path for Google Cloud Storage stream
	 * @param  string $bucket
	 * @param  string $path
	 * @return string
	 */
	protected function getStoragePath($bucket, $path)
	{
		return 'gs://' . $bucket . ($path ? '/' . $path : $path);
	}
}