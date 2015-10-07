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

namespace Core\Structures\Admin;
use Core\Helpers\PushBulletHelper;
use Core\Structures\BaseStructure;

/**
 * The helper for pm'ing someone directly and fast
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $title
 * @property string $message
 * @property int $type
 * @property string $extra
 */
class Pm extends BaseStructure
{
	const	TYPE_NOTE = 1,
			TYPE_LINK = 2,
			TYPE_ADDRESS = 3,
			TYPE_LIST = 4,
			TYPE_FILE = 5;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->message = null;
		$this->type = self::TYPE_NOTE;
		$this->extra = null;
	}

	/**
	 * Send the pm
	 * @return bool
	 */
	public function send()
	{
		$title = config('app.specs.sitename') . ': ' . $this->title;

		if (!$this->hasErrors() && self::hasBeenLongEnoughForThisMessage($title, $this->message))
		{
			return $this->sendPushBullet($title);
		}

		return false;
	}

	/**
	 * Send pm via pushBullet
	 * @param string $title
	 * @return bool
	 */
	private function sendPushBullet($title)
	{
		switch($this->type)
		{
			case self::TYPE_LINK:
				PushBulletHelper::getInstance()->sendLink($title, $this->extra, $this->message);
				break;
			case self::TYPE_ADDRESS:
				PushBulletHelper::getInstance()->sendAddress($title, $this->extra);
				break;
			case self::TYPE_LIST:
				PushBulletHelper::getInstance()->sendList($title, $this->extra);
				break;
			case self::TYPE_FILE:
				PushBulletHelper::getInstance()->sendFile($title, $this->extra, $this->message);
				break;
			case self::TYPE_NOTE:
			default:
				PushBulletHelper::getInstance()->sendNote($title, $this->message);
				break;
		}
		return true;
	}

	/**
	 * Check if this specific message has been sent already
	 * @param string $title
	 * @param string $message
	 * @return bool
	 */
	private static function hasBeenLongEnoughForThisMessage($title, $message)
	{
		$key = 'PmHelper_' . substr(md5($title . $message), 0, 7);

		if (app('cache')->has($key))
		{
			return false;
		}

		app('cache')->put($key, true, 10);

		return true;
	}

}