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

namespace Core\Helpers;
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
	/**
	 * Instance of the pushbullet-helper
	 * @var PushBulletHelper
	 */
	private static $instance;

	/**
	 * Fetch instance of the pushbullet-helper
	 * @return PushBulletHelper
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new PushBulletHelper();
		}

		return self::$instance;
	}

	/**
	 * Instance of PushBullet
	 * @var \PHPushbullet\PHPushbullet
	 */
	private $pushBullet;

	/**
	 * Constructor
	 */
	private function __construct()
	{
		$this->pushBullet = new PHPushbullet(ConfigHelper::get('core.pm.pushbullet.token'));
	}

	/**
	 * Fetch the account to send to
	 * @return string
	 */
	private function getAccount()
	{
		return ConfigHelper::get('core.pm.pushbullet.account');
	}

	/**
	 * Send note
	 * @param string $title
	 * @param string $message
	 */
	public function sendNote($title, $message)
	{
		$this->pushBullet->user($this->getAccount())->note($title, $message);
	}

	/**
	 * Send link
	 * @param string $title
	 * @param string $url
	 * @param string $message
	 */
	public function sendLink($title, $url, $message)
	{
		$this->pushBullet->user($this->getAccount())->link($title, $url, $message);
	}

	/**
	 * Send address
	 * @param string $title
	 * @param string $address
	 */
	public function sendAddress($title, $address)
	{
		$this->pushBullet->user($this->getAccount())->address($title, $address);
	}

	/**
	 * Send list
	 * @param string $title
	 * @param array $list
	 */
	public function sendList($title, $list)
	{
		$this->pushBullet->user($this->getAccount())->list($title, $list);
	}

	/**
	 * Send file
	 * @param string $title
	 * @param string $fileUrl
	 * @param string $message
	 */
	public function sendFile($title, $fileUrl, $message)
	{
		$this->pushBullet->user($this->getAccount())->file($title, $fileUrl, $message);
	}

}