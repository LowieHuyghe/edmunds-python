<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Helpers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * The helper for pm'ing someone directly and fast
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class PmHelper extends BaseHelper
{
	const	TYPE_NOTE = 1,
			TYPE_LINK = 2,
			TYPE_ADDRESS = 3,
			TYPE_LIST = 4,
			TYPE_FILE = 5;

	const	CACHE_KEY = 'PmHelper_',
			CACHE_EXPIRE = 10;

	/**
	 * Pm the admin
	 * @param string $title
	 * @param string $message (Irrelevant for TYPE_ADDRESS and TYPE_LIST)
	 * @param int $type
	 * @param string $extra
	 */
	public static function pmAdmin($title, $message = null, $type = self::TYPE_NOTE, $extra = null)
	{
		$title = Config::get('app.specs.sitename') . ': ' . $title;

		if (self::hasBeenLongEnoughForThisMessage($title, $message))
		{
			switch($type)
			{
				case self::TYPE_LINK:
					PushBulletHelper::getInstance()->sendLink($title, $extra, $message);
					break;
				case self::TYPE_ADDRESS:
					PushBulletHelper::getInstance()->sendAddress($title, $extra);
					break;
				case self::TYPE_LIST:
					PushBulletHelper::getInstance()->sendList($title, $extra);
					break;
				case self::TYPE_FILE:
					PushBulletHelper::getInstance()->sendFile($title, $extra, $message);
					break;
				case self::TYPE_NOTE:
				default:
					PushBulletHelper::getInstance()->sendNote($title, $message);
					break;
			}
		}
	}

	/**
	 * Check if this specific message has been sent already
	 * @param string $title
	 * @param string $message
	 * @return bool
	 */
	private static function hasBeenLongEnoughForThisMessage($title, $message)
	{
		$key = self::CACHE_KEY . substr(md5($title . $message), 0, 7);

		if (Cache::has($key))
		{
			return false;
		}

		Cache::put($key, true, self::CACHE_EXPIRE);

		return true;
	}

}