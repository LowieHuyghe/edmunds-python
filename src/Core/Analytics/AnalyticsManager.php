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

namespace Core\Analytics;

use Core\Analytics\Drivers\GaWarehouse;
use Core\Analytics\Drivers\NewrelicWarehouse;
use Core\Analytics\Drivers\PiwikWarehouse;
use Core\Bases\Analytics\BaseWarehouse;
use Core\Bases\Structures\BaseStructure;

/**
 * The analytics manager
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class AnalyticsManager extends BaseStructure
{
	/**
	 * The driver
	 * @var string
	 */
	protected $driver;

	/**
	 * The warehouses
	 * @var array
	 */
	protected $warehouses = [];

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
	 * Get a warehouse by name
	 * @param  string $name
	 * @return BaseWarehouse
	 */
	public function warehouse($name = null)
	{
		$name = $name ?: $this->getDefaultDriver();

		return $this->warehouses[$name] = $this->get($name);
	}

	/**
	 * Create a new piwik warehouse
	 * @return PiwikWarehouse
	 */
	protected function createPiwikDriver()
	{
		if (!isset($this->warehouses['piwik']))
		{
			$this->warehouses['piwik'] = new PiwikWarehouse();
		}
		return $this->warehouses['piwik'];
	}

	/**
	 * Create a new google analytics warehouse
	 * @return GaWarehouse
	 */
	protected function createGaDriver()
	{
		if (!isset($this->warehouses['ga']))
		{
			$this->warehouses['ga'] = new GaWarehouse();
		}
		return $this->warehouses['ga'];
	}

	/**
	 * Create a new new relic warehouse
	 * @return NewrelicWarehouse
	 */
	protected function createNewrelicDriver()
	{
		if (!isset($this->warehouses['newrelic']))
		{
			$this->warehouses['newrelic'] = new NewrelicWarehouse();
		}
		return $this->warehouses['newrelic'];
	}

	/**
	 * Get the name of the default driver
	 * @return string
	 */
	protected function getDefaultDriver()
	{
		return $this->driver ?? config('app.analytics.default', null);
	}

	/**
	 * Fetch a warehouse by name
	 * @param  strin $name
	 * @return BaseWarehouse
	 */
	protected function get($name)
	{
		if (!isset($this->warehouses[$name]))
		{
			$this->warehouses[$name] = call_user_func(array($this, 'create' . ucfirst($name) . 'Driver'));
		}
		return $this->warehouses[$name];
	}

	/**
	 * Check if analytics are enabled
	 * @return boolean
	 */
	public static function isEnabled()
	{
		return config('app.analytics.enabled', true);
	}
}
