<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Queue;

use Edmunds\Queue\QueueJob;
use Edmunds\Bases\Structures\BaseStructure;

/**
 * The queue to use
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
