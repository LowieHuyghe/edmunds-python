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

namespace Core\Structures\Admin\Pm;
use Core\Structures\BaseStructure;
use PHPushbullet\PHPushbullet;

/**
 * The helper to use PushBullet
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class PushBullet extends BaseStructure
{
	/**
	 * Instance of the pushbullet-helper
	 * @var PushBullet
	 */
	private static $instance;

	/**
	 * Fetch instance of the pushbullet-helper
	 * @return PushBullet
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new PushBullet();
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
	public function __construct()
	{
		$this->pushBullet = new PHPushbullet(env('CORE_ADMIN_PM_PUSHBULLET_TOKEN'));
	}

	/**
	 * Fetch the account to send to
	 * @return string
	 */
	private function getAccount()
	{
		return env('CORE_ADMIN_PM_PUSHBULLET_ACCOUNT');
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