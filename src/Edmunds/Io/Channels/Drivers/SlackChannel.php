<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */

namespace Edmunds\Io\Channels\Drivers;

use Edmunds\Bases\Io\Channels\BaseChannel;
use Exception;

/**
 * The driver for the slack channel
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */
class SlackChannel extends BaseChannel
{
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

		$hook = config('app.io.channel.slack.hook');
		if (!$hook)
		{
			throw new Exception('Slack-hook has not been set (app.io.channel.slack.hook).');
		}

		$settings = array(
			'username' => app()->getName(),
		);

		if ($channel = config('app.io.channel.slack.channel'))
		{
			$settings['channel'] = $channel;
		}
		if ($icon = config('app.io.channel.slack.icon'))
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
		return $this->send($title, 'Info', $body, 'good', config('app.io.channel.slack.channel.info'));
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function warning($title, $body = null)
	{
		return $this->send($title, 'Warning', $body, 'warning', config('app.io.channel.slack.channel.warning'));
	}

	/**
	 * Send the pm
	 * @param string $title
	 * @param string $body
	 * @return bool
	 */
	public function error($title, $body = null)
	{
		return $this->send($title, 'Error', $body, 'danger', config('app.io.channel.slack.channel.error'));
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
		if ($this->hasBeenLongEnough($bodyTitle, $title, $body))
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
		}

		return true;
	}
}
