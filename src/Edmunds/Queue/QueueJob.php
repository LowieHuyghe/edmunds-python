<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Queue;

use Edmunds\Bases\Jobs\BaseJob;
use Edmunds\Queue\Queue;
use Edmunds\Registry;

/**
 * Queue to use
 */
class QueueJob extends BaseJob
{
	/**
	 * @var callable
	 */
	private $callable;

	/**
	 * @var array
	 */
	private $args;

	/**
	 * @var int
	 */
	private $attempts;

	/**
	 * Constructor
	 * @param callable $callable
	 * @param array $args
	 * @param int $attempts
	 */
	public function __construct($callable, $args = array(), $queue = Queue::QUEUE_DEFAULT, $attempts = 0)
	{
		$this->callable = $callable;
		$this->args = $args;
		$this->attempts = $attempts;

		$this->onQueue($queue);
	}

	/**
	 * Execute the job.
	 */
	public function handle()
	{
		if ($this->attempts == 0 || $this->attempts() <= $this->attempts)
		{
			call_user_func_array($this->callable, $this->args);
		}
	}

	/**
	 * Queue this job
	 * @return \Illuminate\Http\Response
	 */
	public function dispatch()
	{
		return Registry::queue()->dispatch($this);
	}
}
