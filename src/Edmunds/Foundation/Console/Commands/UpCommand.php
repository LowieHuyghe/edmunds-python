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
 * The command for putting the app back live.
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
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