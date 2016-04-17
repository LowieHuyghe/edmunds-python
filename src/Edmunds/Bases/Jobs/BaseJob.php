<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Bases\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job base to extend from
 */
class BaseJob implements ShouldQueue
{
	use Queueable, InteractsWithQueue, SerializesModels;

	/**
	 * Execute the job.
	 * @return void
	 */
	public function handle()
	{
		//
	}
}
