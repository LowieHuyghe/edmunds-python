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

use Core\Analytics\AnalyticsManager;
use Core\Bases\Analytics\BaseWarehouse;
use Core\Bases\Structures\BaseStructure;
use Core\Registry\Admin\Pm;

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
	 * Registry
	 * @var array
	 */
	private static $registry = array();

	/**
	 * Get a instance of the cache
	 * @param string $store
	 * @return Cache
	 */
	public static function cache($store = null)
	{
		if (!isset(self::$registry['cache'][$store ?: 0]))
		{
			self::$registry['cache'][$store ?: 0] = new Cache($store);
		}

		return self::$registry['cache'][$store ?: 0];
	}

	/**
	 * Get a instance of the db
	 * @param string $connection
	 * @return Db
	 */
	public static function db($connection = null)
	{
		if (!isset(self::$registry['db'][$connection ?: 0]))
		{
			self::$registry['db'][$connection ?: 0] = new Db($connection);
		}

		return self::$registry['db'][$connection ?: 0];
	}

	/**
	 * Get a instance of the queue
	 * @param string $driver
	 * @return Queue
	 */
	public static function queue($driver = null)
	{
		if (!isset(self::$registry['queue'][$driver ?: 0]))
		{
			self::$registry['queue'][$driver ?: 0] = new Queue($driver);
		}

		return self::$registry['queue'][$driver ?: 0];
	}

	/**
	 * Get a instance of the Pm
	 * @param string $driver
	 * @return Pm
	 */
	public static function pm($driver = null)
	{
		if (!isset(self::$registry['adminPm'][$driver ?: 0]))
		{
			self::$registry['adminPm'][$driver ?: 0] = new Pm($driver);
		}

		return self::$registry['adminPm'][$driver ?: 0];
	}

	/**
	 * Get a instance of a warehouse
	 * @param string $driver
	 * @return BaseWarehouse
	 */
	public static function warehouse($driver = null)
	{
		if (!isset(self::$registry['warehouse'][$driver ?: 0]))
		{
			self::$registry['warehouse'][$driver ?: 0] = (new AnalyticsManager($driver))->warehouse();
		}

		return self::$registry['warehouse'][$driver ?: 0];
	}
}
