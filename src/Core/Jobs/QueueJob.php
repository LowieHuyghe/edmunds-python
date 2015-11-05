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

namespace Core\Jobs;

use Core\Bases\Jobs\BaseJob;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Queue base to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class QueueJob extends BaseJob implements SelfHandling, ShouldQueue
{
	use InteractsWithQueue, SerializesModels;

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
	public function __construct($callable, $args = array(), $attempts = 1)
	{
		$this->callable = $callable;
		$this->args = $args;
		$this->attempts = $attempts;
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
}
