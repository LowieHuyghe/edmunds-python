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

namespace Core\Structures\Registry;

use Core\Jobs\BaseQueue;
use Core\Bases\Structures\BaseStructure;

/**
 * The queue to use
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Queue extends BaseStructure
{
	/**
	 * The default store to load from cache
	 * @var string
	 */
	private $driver;

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
	 * Dispatch a job to its appropriate handler.
	 * @param  BaseQueue  $job
	 * @param string $conveyor
	 * @return mixed
	 */
	public function dispatch($job, $conveyor = null)
	{
		if ($conveyor)
		{
			$job = $job->onQueue($conveyor);
		}
		return app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
	}

	/**
	 * Marshal a job and dispatch it to its appropriate handler.
	 * @param  BaseQueue  $job
	 * @param  array  $array
	 * @param string $conveyor
	 * @return mixed
	 */
	public function dispatchFromArray($job, array $array, $conveyor = null)
	{
		if ($conveyor)
		{
			$job = $job->onQueue($conveyor);
		}
		return app('Illuminate\Contracts\Bus\Dispatcher')->dispatchFromArray($job, $array);
	}

	/**
	 * Marshal a job and dispatch it to its appropriate handler.
	 * @param  BaseQueue  $job
	 * @param  \ArrayAccess  $source
	 * @param  array  $extras
	 * @param string $conveyor
	 * @return mixed
	 */
	public function dispatchFrom($job, ArrayAccess $source, $extras = [], $conveyor = null)
	{
		if ($conveyor)
		{
			$job = $job->onQueue($conveyor);
		}
		return app('Illuminate\Contracts\Bus\Dispatcher')->dispatchFrom($job, $source, $extras);
	}

}
