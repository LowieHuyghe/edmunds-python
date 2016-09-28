<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Console;

use Edmunds\Application;
use Edmunds\Console\Scheduling\Schedule;
use Edmunds\Foundation\Console\Commands\DownCommand;
use Edmunds\Foundation\Console\Commands\UpCommand;
use Edmunds\Localization\Commands\SyncCommand;
use Edmunds\Localization\Commands\UpdateGeoIPCommand;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule as LumenSchedule;

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
	 * @param  \Edmunds\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(LumenSchedule $schedule)
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
		$this->app->instance(
			'Illuminate\Console\Scheduling\Schedule', $schedule = new Schedule
		);

		$this->schedule($schedule);
	}
}
