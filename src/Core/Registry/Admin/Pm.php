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

namespace Core\Registry\Admin;

use Core\Bases\Structures\BaseStructure;
use Core\Registry\Admin\Pm\Slack;
use Core\Registry\Registry;

/**
 * The helper for pm'ing someone directly and fast
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Pm extends BaseStructure implements PmInterface
{
	/**
	 * The default driver to load from cache
	 * @var string
	 */
	public $driver;

	/**
	 * Constructor
	 * @param string $driver
	 */
	public function __construct($driver = null)
	{
		parent::__construct();

		$this->driver = strtolower($driver ?: env('CORE_ADMIN_PM_DRIVER'));
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function info($title, $body = null)
	{
		return $this->send('info', $title, $body);
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function warning($title, $body = null)
	{
		return $this->send('warning', $title, $body);
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function error($title, $body = null)
	{
		return $this->send('error', $title, $body);
	}

	/**
	 * Send the pm
	 * @param  string $type
	 * @param  string $title
	 * @param  string $body
	 * @return bool
	 */
	protected function send($type, $title, $body = null)
	{
		if (self::hasBeenLongEnough($type, $title, $body))
		{
			switch ($this->driver)
			{
				case 'slack':
				default:
					$class = Slack::class;
			}

			return $class::getInstance()->$type($title, $body);
		}

		return true;
	}

	/**
	 * Check if this specific message has been sent already
	 * @param string $type
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	private static function hasBeenLongEnough($type, $title, $body = null)
	{
		return true;
		$key = 'pm_' . substr(md5(json_encode($type) . json_encode($title) . json_encode($body)), 0, 7);

		if (Registry::cache()->has($key))
		{
			return false;
		}

		Registry::cache()->save($key, true, 10);

		return true;
	}

}