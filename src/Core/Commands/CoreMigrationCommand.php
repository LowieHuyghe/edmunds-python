<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

namespace LH\Core\Commands;

/**
 * CoreMigration class responsible for updating the database
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class CoreMigrationCommand extends BaseCommand
{
	/**
	 * The console command name.
	 * @var string
	 */
	protected $name = 'coremigrate';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'The class responsable to run all migrations.';

	/**
	 * All the tables that can be migrated
	 * @var string[]
	 */
	private $classes;


	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function fire()
	{
		$allversions = $this->option('allversions');
		$currentversions = $this->option('currentversions');
		$goto = $this->option('goto');
		$update = $this->option('update');
		$rollback = $this->option('rollback');
		$refresh = $this->option('refresh');
		$seed = $this->option('seed');

		if ($allversions) {
			$this->printAvailableVersions();
		} else if ($currentversions) {
			$this->printCurrentVersion();
		} else if ($goto) {
			$this->update($goto);
		} else if ($update) {
			$this->update();
		} else if ($rollback) {
			$this->rollback();
		} else if ($refresh) {
			$this->refresh();
		} else if ($seed) {
			$this->seed();
		} else {
			$this->info('No input detected. Try --help (-h) for more info.');
		}
	}
}
