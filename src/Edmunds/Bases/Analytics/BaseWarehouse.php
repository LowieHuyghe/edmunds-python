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

namespace Edmunds\Bases\Analytics;

use Edmunds\Analytics\AnalyticsManager;
use Edmunds\Bases\Analytics\Tracking\BaseLog;
use Edmunds\Bases\Structures\BaseStructure;
use Edmunds\Queue\Queue;
use Edmunds\Queue\QueueJob;
use Edmunds\Registry;

/**
 * The warehouse base to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class BaseWarehouse extends BaseStructure
{
	/**
	 * The driver used
	 * @var string
	 */
	protected $driver;

	/**
	 * All the logs bundled
	 * @var BaseLog[]
	 */
	protected $logs = array();

	/**
	 * Parameter mapping
	 * @var array
	 */
	protected $parameterMapping = array();

	/**
	 * The constructor
	 * @param string $driver
	 */
	public function __construct($driver)
	{
		parent::__construct();

		$this->driver = $driver;
	}

	/**
	 * Log something
	 * @param  BaseLog $log
	 * @return void
	 */
	public function log($log)
	{
		if (AnalyticsManager::isEnabled())
		{
			$this->logs[] = $log;
		}
	}

	/**
	 * Flush all the saved up logs
	 */
	public function flush()
	{
		$this->logs = array();
	}

	/**
	 * Queue something
	 * @param  array $argumentsArray
	 */
	protected function queue($callable, $argumentsArray)
	{
		(new QueueJob($callable, $argumentsArray, Queue::QUEUE_LOG, 1))->dispatch();
	}

	/**
	 * Get the assignments for custom parameters
	 * @param  BaseLog $log
	 * @param  array $customConfigName
	 * @param  string $parameter
	 * @return array
	 */
	protected function getCustomAssignments($log, $customConfigName, $parameter)
	{
		$custom = config('app.analytics.' . $this->driver . '.custom.' . $customConfigName, array());
		$assigns = array();

		foreach ($custom as $key => $value)
		{
			if (isset($log->$value))
			{
				$assigns[str_replace('{0}', $key, $parameter)] = $log->$value;
			}
		}

		return $assigns;
	}
}
