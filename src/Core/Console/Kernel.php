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

namespace Core\Console;

use Core\Application;
use Core\Console\Commands\Maintenance\DownCommand;
use Core\Console\Commands\Maintenance\UpCommand;
use Core\Console\Commands\Translation\SyncCommand;
use Core\Console\Commands\UpdateGeoIPCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

/**
 * The kernel for the console
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
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
     * Create a new console kernel instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
    	$this->commands = array_merge($this->commands, array(

			UpdateGeoIPCommand::class,
			DownCommand::class,
			UpCommand::class,
			SyncCommand::class,

    	));

    	parent::__construct($app);
    }

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('updategeoip')->weekly();
	}
}
