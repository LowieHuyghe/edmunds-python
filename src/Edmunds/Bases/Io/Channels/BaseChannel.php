<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */

namespace Edmunds\Bases\Io\Channels;

use Edmunds\Bases\Structures\BaseStructure;
use Edmunds\Registry;

/**
 * The channel base to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
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
