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

use Illuminate\Database\Eloquent\Collection;
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
	/**
	 * All the notifications
	 * @var Notification[]
	 */
	private static $notifications = array();

	/**
	 * Add a notification
	 * @param string $title
	 * @param string $body
	 * @param string $url
	 * @param array $options
	 * @param string $type
	 * @param string $subtype
	 * @return Notification
	 */
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

		return $notification;
	}

	/**
	 * Return all the notifications and filter if wanted
	 * @param string $type
	 * @param string $subtype
	 * @return Collection
	 */
	public static function get($type = null, $subtype = null)
	{
		//If nothing specified, just return all
		if (!$type && !$subtype)
		{
			return collect(self::$notifications);
		}

		//Filter
		$notifications = array();
		foreach (self::$notifications as $notification)
		{
			if ($type && $notification->type != $type)
			{
				continue;
			}
			if ($subtype && $notification->subtype != $subtype)
			{
				continue;
			}
			$notifications[] = $notification;
		}

		return collect($notifications);
	}

	/**
	 * Clear all the notifications and of certain type if wanted
	 * @param string $type
	 * @param string $subtype
	 */
	public static function clear($type = null, $subtype = null)
	{
		//If nothing specified, just return all
		if (!$type && !$subtype)
		{
			self::$notifications = array();
		}

		//Filter
		foreach (self::$notifications as $key => $notification)
		{
			if ($type && $notification->type != $type)
			{
				continue;
			}
			if ($subtype && $notification->subtype != $subtype)
			{
				continue;
			}
			unset(self::$notifications[$key]);
		}
	}

}
