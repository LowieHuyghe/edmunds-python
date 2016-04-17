<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Console\Commands;

use Edmunds\Bases\Commands\BaseCommand;

/**
 * The command for putting the app back live.
 */
class UpCommand extends BaseCommand {
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'up';
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Bring the application out of maintenance mode";
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		@unlink(app()->storagePath('framework/down'));
		$this->info('Application is now live.');
	}

}