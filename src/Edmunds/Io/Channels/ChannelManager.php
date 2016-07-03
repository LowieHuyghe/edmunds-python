<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Io\Channels;

use Edmunds\Bases\Io\Channels\BaseChannel;
use Edmunds\Bases\Structures\BaseStructure;
use Edmunds\Io\Channels\Drivers\SlackChannel;

/**
 * The pm manager
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
		return $this->driver ?: config('app.io.channel.default', null);
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
