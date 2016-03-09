<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Queue;

use Core\Queue\QueueJob;
use Core\Bases\Structures\BaseStructure;

/**
 * The queue to use
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */
class Queue extends BaseStructure
{
	const	QUEUE_DEFAULT		= null,
			QUEUE_LOG			= 'log';
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
	 * @param QueueJob $job
	 * @return \Illuminate\Http\Response
	 */
	public function dispatch($job)
	{
		return app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
	}

}
