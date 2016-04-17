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

namespace Edmunds\Foundation\Console\Commands;
use Edmunds\Bases\Commands\BaseCommand;

/**
 * The command for putting the app in maintenance mode.
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
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