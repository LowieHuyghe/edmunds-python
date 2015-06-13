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

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use LH\Core\Database\Migrations\BaseMigration;

/**
 * CoreMigration class responsible for updating the database
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class CoreMigrateCommand extends BaseCommand
{
	/**
	 * The command name and signature.
	 * @var string
	 */
	protected $signature = 'coremigrate
			{--init : Init the database for using CoreMigrations.}
			{--allversions : Print the available database versions.}
			{--currentversion : Print the current database version.}
			{--upgrade : Upgrade the database to the specified version (v).}
			{--downgrade : Downgrade the database to the specified version (v).}
			{--refresh : Delete the whole database and create it again.}
			{--seed : Seed the database with init-data.}
	';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'The class responsible to run all migrations.';

	/**
	 * All the tables that can be migrated
	 * @var BaseMigration[]
	 */
	protected $migrations = array();

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		for ($i = 0; $i < count($this->migrations); ++$i)
		{
			$this->migrations[$i] = new $this->migrations[$i]();
		}
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle()
	{
		if ($this->option('init'))
		{
			$this->info('Initiating the database for usage of CoreMigration, run:');
			$this->call('migrate', array('--path' => 'vendor/lh/core/src/core/database/migrations'));
		}
		elseif ($this->option('allversions'))
		{
			$this->printAvailableVersions();
		}
		elseif ($this->option('currentversion'))
		{
			$this->printCurrentVersion();
		}
		elseif ($this->option('upgrade'))
		{
			$this->upgrade();
		}
		elseif ($this->option('downgrade'))
		{
			$this->downgrade();
		}
		elseif ($this->option('refresh'))
		{
			$this->refresh();
		}
		elseif ($this->option('seed'))
		{
			$this->seed();
		}
		else
		{
			$this->info('No input detected. Try --help (-h) for more info.');
		}
	}

	/**
	 * Upgrade the database
	 */
	private function upgrade()
	{
		$this->info('Initiating upgrading database from ' . implode(', ', array_flatten($this->getCurrentVersions())));
		$newVersion = $this->choice('To what version may I ask?', $this->getAvailableVersions(), false);
		if (!in_array($newVersion, $this->getAvailableVersions()))
		{
			$this->info('No valid version specified');
			return;
		}

		$updatedSomething = false;
		foreach ($this->migrations as $migration)
		{
			if ($migration->up($newVersion))
			{
				$this->info("\tUpgraded " . get_class($migration) . " to version $newVersion");
				$updatedSomething = true;
			}
		}

		//Print outcome
		if ($updatedSomething)
		{
			$this->info("Successfully upgraded database to $newVersion");
		}
		else
		{
			$this->info('Nothing to upgrade');
		}
	}

	/**
	 * Downgrade the database
	 */
	private function downgrade()
	{
		$this->info('Initiating downgrading database from ' . implode(', ', array_flatten($this->getCurrentVersions())));
		$versions = $this->getAvailableVersions();
		array_unshift($versions, '0');
		$newVersion = $this->choice('To what version may I ask?', $versions, false);
		if (!in_array($newVersion, $versions))
		{
			$this->info('No valid version specified');
			return;
		}

		$updatedSomething = false;
		foreach ($this->migrations as $migration)
		{
			if ($migration->down($newVersion))
			{
				$this->info("\tDowngraded " . get_class($migration) . " to version $newVersion");
				$updatedSomething = true;
			}
		}

		//Print outcome
		if ($updatedSomething)
		{
			$this->info("Successfully downgraded database to $newVersion");
		}
		else
		{
			$this->info('Nothing to downgrade');
		}
	}

	/**
	 * Delete the whole database and update it again.
	 */
	private function refresh()
	{
		$versions = $this->getAvailableVersions();

		if (!empty($versions))
		{
			$this->downgrade('0');
			$this->upgrade($versions[count($versions)-1]);
		}
	}

	/**
	 * Seed the database with init-data.
	 */
	private function seed()
	{
		if (!$this->confirm('Are you sure you want to seed the database (and thus delete the records in those tables)?', false))
		{
			return false;
		}
		$this->info('Initiating seeding the database');

		$seededSomething = false;
		foreach ($this->migrations as $migration)
		{
			if ($migration->seed())
			{
				$this->info("\tSeeded " . get_class($migration));
				$seededSomething = true;
			}
		}

		//Print outcome
		if ($seededSomething)
		{
			$this->info('Successfully seeded the database');
		}
		else
		{
			$this->info('Nothing to seed');
		}
	}

	/**
	 * Print the current version
	 */
	private function printCurrentVersion()
	{
		$versions = $this->getCurrentVersions();

		if ($versions)
		{
			$this->info('The database is currently at version' . (count($versions) > 1 ? 's' : '') . ': ' . implode(', ', array_flatten($versions)));
		}
		else
		{
			$this->info('The database has not yet been initiated');
		}
	}

	/**
	 * Return the current versions in the db
	 * @return string[]
	 */
	private function getCurrentVersions()
	{
		DB::setFetchMode(\PDO::FETCH_COLUMN);
		$versions = DB::select('SELECT version FROM core_migrations GROUP BY version');
		DB::setFetchMode(\PDO::FETCH_OBJ);

		if ($versions)
		{
			BaseMigration::sortVersions($versions);
			return $versions;
		}
		else
		{
			return array( 0 );
		}
	}

	/**
	 * Print the available versions of the database
	 */
	private function printAvailableVersions()
	{

		$versions = $this->getAvailableVersions();
		if (!empty($versions))
		{
			$this->info('Available versions: ' . implode(', ', $versions));
		}
		else
		{
			$this->info('No versions available');
		}
	}

	/**
	 * Return the available versions
	 * @return string[]
	 */
	private function getAvailableVersions()
	{
		//Fetch
		$versions = array();
		foreach ($this->migrations as $migration)
		{
			$versions = array_merge($versions, $migration->getAllVersions());
		}
		array_unique($versions);

		//Sort
		if (!empty($versions))
		{
			BaseMigration::sortVersions($versions);
		}

		return $versions;
	}
}
