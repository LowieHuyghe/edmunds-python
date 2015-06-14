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
use PHPushbullet\PHPushbullet;

/**
 * The helper to use PushBullet
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class PushBulletHelper extends BaseHelper
{
	private static $account = 'huyghe.lowie@gmail.com';
	private static $token = 'jue2B1zhgiU6cIwxAc55vY0rKfjL5iC5';

	/**
	 * Instance of PushBullet
	 * @var \PHPushbullet\PHPushbullet
	 */
	private static $pushBullet;

	/**
	 * Get an instance of PushBullet
	 * @return \PHPushbullet\PHPushbullet
	 */
	private static function getPushBullet()
	{
		if (!isset(self::$pushBullet))
		{
			self::$pushBullet = new PHPushbullet(self::$token);
		}

		return self::$pushBullet;
	}

	/**
	 * Send note
	 * @param string $title
	 * @param string $message
	 */
	public static function sendNote($title, $message)
	{
		self::getPushBullet()->user(self::$account)->note($title, $message);
	}

	/**
	 * Send link
	 * @param string $title
	 * @param string $url
	 * @param string $message
	 */
	public static function sendLink($title, $url, $message)
	{
		self::getPushBullet()->user(self::$account)->link($title, $url, $message);
	}

	/**
	 * Send address
	 * @param string $title
	 * @param string $address
	 */
	public static function sendAddress($title, $address)
	{
		self::getPushBullet()->user(self::$account)->address($title, $address);
	}

	/**
	 * Send list
	 * @param string $title
	 * @param array $list
	 */
	public static function sendList($title, $list)
	{
		self::getPushBullet()->user(self::$account)->list($title, $list);
	}

	/**
	 * Send file
	 * @param string $title
	 * @param string $fileUrl
	 * @param string $message
	 */
	public static function sendFile($title, $fileUrl, $message)
	{
		self::getPushBullet()->user(self::$account)->file($title, $fileUrl, $message);
	}

}