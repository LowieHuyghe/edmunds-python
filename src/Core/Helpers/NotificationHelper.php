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

use Symfony\Component\HttpFoundation\File\UploadedFile;
use LH\Core\Models\Notification;

/**
 * The helper responsible for notifications
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class NotificationHelper extends BaseHelper
{

	private static $notifications = array();

	public static function add($title, $body = null, $url = null, $options = array(), $type = null, $subtype = null)
	{
		$notification = new Notification();
		$notification->title = $title;
		$notification->body = $body;
		$notification->url = $url;
		$notification->options = $options;
		$notification->type = $type;
		$notification->subtype = $subtype;

		self::$notifications[] = $notification;
	}

	public static function clear()
	{
		self::$notifications = array();
	}

	public static function get($type = null, $subtype = null)
	{

	}

}
