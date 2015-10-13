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

namespace Core\Structures\Registry;

use Core\Structures\BaseStructure;

/**
 * A base for the structures to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Registry extends BaseStructure
{
	/**
	 * Instance of the registry
	 * @var Registry
	 */
	private static $instance;

	/**
	 * Fetch instance of the response-helper
	 * @return Registry
	 */
	public static function current()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Registry();
		}
		return self::$instance;
	}

	/**
	 * Registry
	 * @var array
	 */
	private $registry = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get a instance of the cache
	 * @param string $store
	 * @return Cache
	 */
	public function cache($store = null)
	{
		if (!isset($this->registry['cache'][$store ?: 0]))
		{
			$this->registry['cache'][$store ?: 0] = new Cache($store);
		}

		return $this->registry['cache'][$store ?: 0];
	}

	/**
	 * Get a instance of the db
	 * @param string $connection
	 * @return Db
	 */
	public function db($connection = null)
	{
		if (!isset($this->registry['db'][$connection ?: 0]))
		{
			$this->registry['db'][$connection ?: 0] = new Db($connection);
		}

		return $this->registry['db'][$connection ?: 0];
	}

	/**
	 * Get a instance of the queue
	 * @param string $driver
	 * @return Queue
	 */
	public function queue($driver = null)
	{
		if (!isset($this->registry['queue'][$driver ?: 0]))
		{
			$this->registry['queue'][$driver ?: 0] = new Queue($driver);
		}

		return $this->registry['queue'][$driver ?: 0];
	}

}
