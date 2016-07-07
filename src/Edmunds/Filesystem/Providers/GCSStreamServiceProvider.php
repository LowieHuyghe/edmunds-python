<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Filesystem\Providers;

use Edmunds\Bases\Providers\BaseServiceProvider;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;

/**
 * The Google Cloud Service filesystem service provider
 */
class GCSStreamServiceProvider extends BaseServiceProvider
{
	/**
	 * Boot the service
	 *
	 * @return void
	 */
	public function boot()
	{
		app('filesystem')->extend('gcsstream', function ($app, $config)
		{
			$links = Arr::get($config, 'links') === 'skip'
				? LocalAdapter::SKIP_LINKS
				: LocalAdapter::DISALLOW_LINKS;

			$permissions = isset($config['permissions']) ? $config['permissions'] : [];

			$root = isset($config['root']) ? $config['root'] : '';
			$path = $this->getStoragePath($config['bucket'], $root);

			return $this->createFlysystem(new LocalAdapter(
				$path, LOCK_EX, $links, $permissions
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
