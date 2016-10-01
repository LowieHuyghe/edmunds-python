<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Patterns;

/**
 * Trait for singletons
 */
trait Singleton
{
	/**
	 * Instance
	 * @var static
	 */
	protected static $instance;

	/**
	 * Get instance
	 * @return static
	 */
	public static function getInstance()
	{
		if ( ! isset(self::$instance))
		{
			self::$instance = new static();
		}

		return self::$instance;
	}
}