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

namespace Core\Registry\Admin\Pm;

use Core\Bases\Structures\BaseStructure;
use Core\Registry\Admin\PmInterface;

/**
 * The helper for pm'ing someone directly and fast with Slack
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Slack extends BaseStructure implements PmInterface
{
	/**
	 * Instance of the slack-helper
	 * @var Slack
	 */
	private static $instance;

	/**
	 * Fetch instance of the slack-helper
	 * @return Response
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Slack();
		}
		return self::$instance;
	}

	/**
	 * Client of Slack
	 * @var \Maknz\Slack\Client
	 */
	protected $client;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$hook = env('CORE_ADMIN_PM_SLACK_HOOK');
		$settings = array(
			'username' => env('APP_NAME'),
		);

		if ($channel = env('CORE_ADMIN_PM_SLACK_CHANNEL'))
		{
			$settings['channel'] = $channel;
		}
		if ($icon = env('CORE_ADMIN_PM_SLACK_ICON'))
		{
			$settings['icon'] = $icon;
		}

		$this->client = new \Maknz\Slack\Client($hook, $settings);
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function info($title, $body = null)
	{
		return $this->send($title, 'Info', $body, 'good', env('CORE_ADMIN_PM_SLACK_CHANNEL_INFO'));
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function warning($title, $body = null)
	{
		return $this->send($title, 'Warning', $body, 'warning', env('CORE_ADMIN_PM_SLACK_CHANNEL_WARNING'));
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function error($title, $body = null)
	{
		return $this->send($title, 'Error', $body, 'danger', env('CORE_ADMIN_PM_SLACK_CHANNEL_ERROR'));
	}

	/**
	 * Sen the pm
	 * @param  string $title
	 * @param  string $body
	 * @param  string $color
	 * @param  string $channel
	 * @return bool
	 */
	protected function send($title, $bodyTitle, $body, $color, $channel = null)
	{
		$client = $this->client;

		if ($channel)
		{
			$client = $client->to($channel);
		}
		if ($body)
		{
			$client = $client->attach(array(
				'fallback' => $body,
				'text' => $body,
				'color' => $color,
			));
		}

		$client->send($title);

		return true;
	}

}