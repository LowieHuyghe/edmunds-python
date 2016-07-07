<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Filesystem\Providers;

use Aws\S3\S3Client;
use Edmunds\Bases\Providers\BaseServiceProvider;
use Illuminate\Support\Arr;
use League\Flysystem\AwsS3v2\AwsS3Adapter;
use League\Flysystem\Filesystem;

/**
 * The Google Cloud Service filesystem service provider
 */
class GCSServiceProvider extends BaseServiceProvider
{
	/**
	 * Boot the service
	 *
	 * @return void
	 */
	public function boot()
	{
		app('filesystem')->extend('gcs', function ($app, $config)
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
		});
	}

	/**
	 * Create a Flysystem instance with the given adapter.
	 *
	 * @param  \League\Flysystem\AdapterInterface  $adapter
	 * @param  array  $config
	 * @return \League\Flysystem\FlysystemInterface
	 */
	protected function createFlysystem(AdapterInterface $adapter, array $config)
	{
		$config = Arr::only($config, ['visibility']);

		return new Flysystem($adapter, count($config) > 0 ? $config : null);
	}
}
