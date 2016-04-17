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

namespace Edmunds\Io\Channels;

use Edmunds\Bases\Io\Channels\BaseChannel;
use Edmunds\Bases\Structures\BaseStructure;
use Edmunds\Io\Channels\Drivers\SlackChannel;

/**
 * The pm manager
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class ChannelManager extends BaseStructure
{
	/**
	 * The driver
	 * @var string
	 */
	protected $driver;

	/**
	 * The channels
	 * @var array
	 */
	protected $channels = [];

	/**
	 * Constructor
	 * @param string $driver
	 */
	public function __construct($driver = null)
	{
		parent::__construct();

		$this->driver = $driver;
	}

	/**
	 * Get a channel by name
	 * @param  string $name
	 * @return BaseChannel
	 */
	public function channel($name = null)
	{
		$name = $name ?: $this->getDefaultDriver();

		return $this->channels[$name] = $this->get($name);
	}

	/**
	 * Create a new slack channel
	 * @return SlackChannel
	 */
	protected function createSlackDriver()
	{
		if (!isset($this->channels['slack']))
		{
			$this->channels['slack'] = new SlackChannel();
		}
		return $this->channels['slack'];
	}

	/**
	 * Get the name of the default driver
	 * @return string
	 */
	protected function getDefaultDriver()
	{
		return $this->driver ?? config('app.io.channel.default', null);
	}

	/**
	 * Fetch a channel by name
	 * @param  strin $name
	 * @return BaseChannel
	 */
	protected function get($name)
	{
		if (!isset($this->channels[$name]))
		{
			$this->channels[$name] = call_user_func(array($this, 'create' . ucfirst($name) . 'Driver'));
		}
		return $this->channels[$name];
	}
}
