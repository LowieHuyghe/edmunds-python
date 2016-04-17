<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Cache;

use Edmunds\Bases\Structures\BaseStructure;

/**
 * The cache to use
 */
class Cache extends BaseStructure
{
	/**
	 * The default store to load from cache
	 * @var string
	 */
	public $store;

	/**
	 * Constructor
	 * @param string $driver
	 */
	public function __construct($driver = null)
	{
		parent::__construct();

		$manager = app('cache');
		$store = $manager->store($driver ?: config('cache.default', 'file'));

		$this->store = $store;
	}

	/**
	 * Get a value from cache
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return $this->store->get($key, $default);
	}

	/**
	 * Check if value exists
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return $this->store->has($key);
	}

	/**
	 * Save a value in cache
	 * @param string $key
	 * @param mixed $value
	 * @param int $minutes 0 = Forever
	 */
	public function set($key, $value, $minutes = 7200)
	{
		if ($minutes)
		{
			$this->store->put($key, $value, $minutes);
		}
		else
		{
			$this->store->forever($key, $value);
		}
	}

	/**
	 * Delete from cache
	 * @param string $key
	 * @return bool Success
	 */
	public function delete($key)
	{
		return $this->store->forget($key);
	}

}
