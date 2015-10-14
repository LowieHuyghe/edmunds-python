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

namespace Core\Structures\Registry\Admin;
use Core\Helpers\PushBulletHelper;
use Core\Structures\BaseStructure;
use Core\Structures\Registry\Registry;

/**
 * The helper for pm'ing someone directly and fast
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Pm extends BaseStructure
{
	const	TYPE_NOTE = 1,
			TYPE_FILE = 2;

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

		$this->driver = $driver ?: config('app.admin.pm.driver');
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function sendNote($title, $body = null)
	{
		$title = config('app.specs.sitename') . ': ' . $title;

		if (self::hasBeenLongEnough(self::TYPE_NOTE, $title, $body))
		{
			switch ($this->driver)
			{
				case 'pushbullet':
				default:
					return $this->sendPushBullet(self::TYPE_NOTE, $title, $body);
			}
		}

		return true;
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $file
	 * @param string $body
	 * @return bool
	 */
	public function sendFile($title, $file, $body = null)
	{
		$title = config('app.specs.sitename') . ': ' . $title;

		if (self::hasBeenLongEnough(self::TYPE_FILE, $title, $body, $file))
		{
			switch ($this->driver)
			{
				case 'pushbullet':
				default:
					return $this->sendPushBullet(self::TYPE_FILE, $title, $body, $file);
			}
		}

		return true;
	}

	/**
	 * Send pm via pushBullet
	 * @param int $type
	 * @param string $title
	 * @param string $body
	 * @param mixed $extra
	 * @return bool
	 */
	private function sendPushBullet($type, $title, $body = null, $extra = null)
	{
		switch($type)
		{
			case self::TYPE_FILE:
				PushBulletHelper::getInstance()->sendFile($title, $extra, $body);
				break;
			case self::TYPE_NOTE:
			default:
				PushBulletHelper::getInstance()->sendNote($title, $body);
				break;
		}
		return true;
	}

	/**
	 * Check if this specific message has been sent already
	 * @param string $title
	 * @param string $body
	 * @param int $type
	 * @param mixed $extra
	 * @return bool
	 */
	private static function hasBeenLongEnough($title, $body = null, $type = null, $extra = null)
	{
		return true;
		$key = 'Pm_' . substr(md5(json_encode($title) . json_encode($body) . json_encode($type) . json_encode($extra)), 0, 7);

		if (Registry::cache()->has($key))
		{
			return false;
		}

		Registry::cache()->save($key, true, 10);

		return true;
	}

}