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

use LH\Core\Database\Migrations\BaseMigration;

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
	 * The command name and signature.
	 * @var string
	 */
	protected $signature = 'coremigrate:{option}';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'The class responsible to run all migrations.';

	/**
	 * All the tables that can be migrated
	 * @var BaseMigration[]
	 */
	private static $migrations = array();

	/**
	 * Add a class for the migrations
	 * @param BaseMigration $instance
	 */
	public static function addMigration($instance)
	{
		self::$migrations[] = $instance;
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle()
	{
		$allVersions = $this->option('allversions');
		$currentVersions = $this->option('currentversions');

		$upgrade = $this->option('upgrade');
		$downgrade = $this->option('downgrade');
		$refresh = $this->option('refresh');

		$seed = $this->option('seed');

		if ($allVersions)
		{
			$this->printAvailableVersions();
		}
		else if ($currentVersions)
		{
			$this->printCurrentVersion();
		}
		else if ($upgrade)
		{
			$this->upgrade();
		}
		else if ($downgrade)
		{
			$this->downgrade();
		}
		else if ($refresh)
		{
			$this->refresh();
		}
		else if ($seed)
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
		$newVersion = $this->ask('To what version may I ask? (' . implode(', ', $this->getAvailableVersions()) . ')');
		if (!in_array($newVersion, $this->getAvailableVersions()))
		{
			$this->info('No valid version specified.');
			return;
		}

		$updatedSomething = false;
		foreach ($this->classes as $schema)
		{
			if ($schema->up($newVersion))
			{
				$this->info("Upgraded " . get_class($schema) . " to version $newVersion");
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
			$this->info('Nothing to upgrade.');
		}
	}

	/**
	 * Downgrade the database
	 */
	private function downgrade()
	{
		$this->info('Initiating downgrading database from ' . implode(', ', array_flatten($this->getCurrentVersions())));
		$newVersion = $this->ask('To what version may I ask? (' . implode(', ', $this->getAvailableVersions()) . ')');
		if (!in_array($newVersion, $this->getAvailableVersions()))
		{
			$this->info('No valid version specified.');
			return;
		}

		$updatedSomething = false;
		foreach ($this->classes as $migration)
		{
			if ($migration->down($newVersion))
			{
				$this->info("Upgraded " . get_class($migration) . " to version $newVersion");
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
			$this->info('Nothing to downgrade.');
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
		if (!$this->confirm('Are you sure you want to seed the database (and thus delete the records in those tables)? [yes|no]', false))
		{
			return false;
		}
		$this->info('Initiating seeding the database');

		$seededSomething = false;
		foreach (self::$migrations as $migration)
		{
			if ($migration->seed())
			{
				$this->info("Seeded " . get_class($migration));
			}
		}

		//Print outcome
		if ($seededSomething)
		{
			$this->info('Successfully seeded the database');
		}
		else
		{
			$this->info('Nothing to seed.');
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
			$this->info('The database is currently at version' . (count($versions) ? 's' : '') . ': ' . implode(', ', array_flatten($versions)));
		}
		else
		{
			$this->info('The database has not yet been initiated.');
		}
	}

	/**
	 * Return the current versions in the db
	 * @return string[]
	 */
	private function getCurrentVersions()
	{
		$versions = DB::select('SELECT version FROM core_migrations GROUP BY version');
		return BaseMigration::sortVersions($versions);
	}

	/**
	 * Print the available versions of the database
	 */
	private function printAvailableVersions()
	{
		$this->info('Available versions: ');

		$versions = $this->getAvailableVersions();
		$this->info(implode(', ', $versions));
	}

	/**
	 * Return the available versions
	 * @return string[]
	 */
	private function getAvailableVersions()
	{
		//Fetch
		$versions = array();
		foreach ($this->classes as $migration)
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

	/**
	 * Get the console command options.
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('allversions', 'av', InputOption::VALUE_NONE, 'Print the available database versions.', null),
			array('currentversions', 'cv', InputOption::VALUE_NONE, 'Print the current database version.', null),

			array('upgrade', 'ug', InputOption::VALUE_NONE, 'Upgrade the database to the specified version (v).', null),
			array('downgrade', 'dg', InputOption::VALUE_NONE, 'Downgrade the database to the specified version (v).', null),
			array('refresh', 're', InputOption::VALUE_NONE, 'Delete the whole database and create it again.', null),

			array('seed', 'se', InputOption::VALUE_NONE, 'Seed the database with init-data.', null),
		);
	}
}
