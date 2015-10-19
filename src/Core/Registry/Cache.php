<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace Core\Registry;

use Core\Bases\Structures\BaseStructure;

/**
 * The cache to use
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
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

		$this->store = $driver;
	}

	/**
	 * Get a value from cache
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return app('cache')->store($this->store)->get($key, $default);
	}

	/**
	 * Check if value exists
	 * @param string $key
	 * @return mixed
	 */
	public function has($key)
	{
		return app('cache')->store($this->store)->has($key);
	}

	/**
	 * Save a value in cache
	 * @param string $key
	 * @param mixed $value
	 * @param int $minutes 0 = Forever
	 */
	public function save($key, $value, $minutes = 60 * 24 * 5)
	{
		if ($minutes)
		{
			app('cache')->store($this->store)->put($key, $value, $minutes);
		}
		else
		{
			app('cache')->store($this->store)->forever($key, $value);
		}
	}

	/**
	 * Delete from cache
	 * @param string $key
	 */
	public function delete($key)
	{
		app('cache')->store($this->store)->forget($key);
	}

}
