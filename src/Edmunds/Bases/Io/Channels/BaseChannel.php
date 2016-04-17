<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Bases\Io\Channels;

use Edmunds\Bases\Structures\BaseStructure;
use Edmunds\Registry;

/**
 * The channel base to extend from
 */
class BaseChannel extends BaseStructure
{
	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function info($title, $body = null)
	{
		return false;
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function warning($title, $body = null)
	{
		return false;
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function error($title, $body = null)
	{
		return false;
	}

	/**
	 * Check if this specific message has been sent already
	 * @param string $type
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	protected function hasBeenLongEnough($type, $title, $body = null)
	{
		$key = 'pm_' . substr(md5(json_encode($type) . json_encode($title) . json_encode($body)), 0, 7);

		if (Registry::cache()->has($key))
		{
			return false;
		}

		Registry::cache()->set($key, true, 10);

		return true;
	}
}
