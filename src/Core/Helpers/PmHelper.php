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

/**
 * The helper for pm'ing someone directly and fast
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class PmHelper
{
	CONST	TYPE_NOTE = 1,
			TYPE_LINK = 2,
			TYPE_ADDRESS = 3,
			TYPE_LIST = 4,
			TYPE_FILE = 5;

	/**
	 * Pm the admin
	 * @param string $title
	 * @param string $message (Irrelevant for TYPE_ADDRESS and TYPE_LIST)
	 * @param int $type
	 * @param string $extra
	 */
	public static function pmAdmin($title, $message, $type = self::TYPE_NOTE, $extra = null)
	{
		switch($type)
		{
			case self::TYPE_LINK:
				PushBulletHelper::sendLink($title, $extra, $message);
				break;
			case self::TYPE_ADDRESS:
				PushBulletHelper::sendAddress($title, $extra);
				break;
			case self::TYPE_LIST:
				PushBulletHelper::sendList($title, $extra);
				break;
			case self::TYPE_FILE:
				PushBulletHelper::sendFile($title, $extra, $message);
				break;
			case self::TYPE_NOTE:
			default:
				PushBulletHelper::sendNote($title, $message);
				break;
		}
	}

}