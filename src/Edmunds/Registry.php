<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds;

use Edmunds\Analytics\AnalyticsManager;
use Edmunds\Bases\Analytics\BaseWarehouse;
use Edmunds\Bases\Structures\BaseStructure;
use Edmunds\Cache\Cache;
use Edmunds\Database\Database;
use Edmunds\Io\Channels\ChannelManager;
use Edmunds\Queue\Queue;

/**
 * A base for the structures to extend from
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
		if ( ! isset(self::$registry['cache'][$store ?: 0]))
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
		if ( ! isset(self::$registry['db'][$connection ?: 0]))
		{
			self::$registry['db'][$connection ?: 0] = new Database($connection);
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
		if ( ! isset(self::$registry['queue'][$driver ?: 0]))
		{
			self::$registry['queue'][$driver ?: 0] = new Queue($driver);
		}

		return self::$registry['queue'][$driver ?: 0];
	}

	/**
	 * Get a instance of the Channel
	 * @param string $driver
	 * @return Channel
	 */
	public static function channel($driver = null)
	{
		if ( ! isset(self::$registry['channel'][$driver ?: 0]))
		{
			self::$registry['channel'][$driver ?: 0] = (new ChannelManager($driver))->channel();
		}

		return self::$registry['channel'][$driver ?: 0];
	}

	/**
	 * Get a instance of a warehouse
	 * @param string $driver
	 * @return BaseWarehouse
	 */
	public static function warehouse($driver = null)
	{
		if ( ! isset(self::$registry['warehouse'][$driver ?: 0]))
		{
			self::$registry['warehouse'][$driver ?: 0] = (new AnalyticsManager($driver))->warehouse();
		}

		return self::$registry['warehouse'][$driver ?: 0];
	}
}