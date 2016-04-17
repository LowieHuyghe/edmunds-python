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
 * The command for putting the app in maintenance mode.
 */
class DownCommand extends BaseCommand {
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'down';
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Put the application into maintenance mode";
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		touch(app()->storagePath('framework/down'));
		$this->comment('Application is now in maintenance mode.');
	}

}