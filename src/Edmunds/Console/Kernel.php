<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Console;

use Edmunds\Gae\Console\Scheduling\Schedule as GaeSchedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

/**
 * The kernel for the console
 */
class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		//
	}

	/**
	 * Define the application's command schedule.
	 *
	 * @return void
	 */
	protected function defineConsoleSchedule()
	{
		// Google App Engine schedule
		if ($this->app->isGae())
		{
			$this->app->instance(
				'Illuminate\Console\Scheduling\Schedule', $schedule = new GaeSchedule
			);

			$this->schedule($schedule);
		}

		else
		{
			return parent::defineConsoleSchedule();
		}
	}
}
