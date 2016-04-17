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

namespace Edmunds\Queue;

use Edmunds\Bases\Jobs\BaseJob;
use Edmunds\Queue\Queue;
use Edmunds\Registry;

/**
 * Queue to use
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
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
	public function __construct($callable, $args = array(), $queue = Queue::QUEUE_DEFAULT, $attempts = 1)
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
		if ($this->attempts() <= $this->attempts)
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
