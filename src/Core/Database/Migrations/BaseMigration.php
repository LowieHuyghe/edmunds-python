<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Database\Migrations;

use LH\Core\Database\Seeders\BaseSeeder;
use LH\Core\Models\CoreMigration;
use Exception;

/**
 * Migration base to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class BaseMigration
{
	/**
	 * Table for deleting the records
	 * @var string
	 */
	protected $table;

	/**
	 * The seeder for this table
	 * @var BaseSeeder
	 */
	private $seeder;

	/**
	 * Constructor
	 * @param BaseSeeder $seeder
	 * @throws Exception
	 */
	function __construct($seeder)
	{
		$this->seeder = $seeder;

		$upVersions = self::getAllVersions('asc', 'up');
		$downVersions = self::getAllVersions('desc', 'down');

		if (count($upVersions) != count($downVersions))
		{
			throw new Exception("Migration class 'up' and 'down' count is not equal.");
		}
	}

	/**
	 * Run the migration of a certain version
	 * @param string $newVersion
	 */
	public function up($newVersion)
	{
		$versions = self::getAllVersions();
		$currentVersion = $this->getCurrentVersion();

		$upgradedSomething = false;
		foreach ($versions as $version)
		{
			if (self::compareVersions($version, $currentVersion) === 1 //Only apply when version is bigger than current version
				&& self::compareVersions($version, $newVersion) <= 0) //Only apply when version is smaller or equal to newVersion
			{
				$this->applyVersion($version, 'up');
				$upgradedSomething = true;
			}
		}

		return $upgradedSomething;
	}

	/**
	 * Reverse the migrations.
	 * @param string $newVersion
	 */
	public function down($newVersion)
	{
		$versions = self::getAllVersions('desc');
		$currentVersion = $this->getCurrentVersion();

		$downgradedSomething = false;
		foreach ($versions as $version)
		{
			if (self::compareVersions($version, $currentVersion) <= 0 //Only apply when version is the current version or smaller
				&& self::compareVersions($version, $newVersion) > 0) //Only apply when version is bigger to newVersion
			{
				$this->applyVersion($version, 'down');
				$downgradedSomething = true;
			}
		}

		return $downgradedSomething;
	}

	/**
	 * Seed the table
	 * @return bool Seeded
	 */
	public function seed()
	{
		$currentVersion = $this->getCurrentVersion();

		if ($currentVersion)
		{
			return $this->seeder->fill($currentVersion);
		}

		return false;
	}

	/**
	 * Get the current version for the migration
	 * @return int
	 */
	protected function getCurrentVersion()
	{
		$migration = CoreMigration::where('migration', '=', get_class($this))->first();

		if ($migration)
		{
			return $migration->version;
		}

		return 0;
	}

	/**
	 * Get the different versions
	 * @param string $order
	 * @param string $prefix
	 * @return array
	 */
	public function getAllVersions($order = 'asc', $prefix = 'up')
	{
		$methods = get_class_methods(get_class($this));

		//Only the methods with that prefix
		$methods = array_filter($methods, function($el) use ($prefix) {
			return strpos($el, $prefix . '_') === 0;
		});

		//Convert to . syntax
		$result = array_map(function($el) use ($prefix) {
			$el = str_replace($prefix . '_', '', $el);
			return implode('.', explode('_', $el));
		}, $methods);

		self::sortVersions($result);

		//Reverse if other order needed
		if (strtolower($order) == 'desc')
		{
			$result = array_reverse($result);
		}

		return $result;
	}

	/**
	 * Apply the given version
	 * @param string $version
	 * @param string $action
	 * @return bool
	 */
	protected function applyVersion($version, $action)
	{
		//Run update
		if ($action == 'up')
		{
			$newVersion = $version;
		}
		elseif ($action == 'down')
		{
			$newVersion = 0;
			$versions = $this->getAllVersions();
			foreach ($versions as $v) {
				if (self::compareVersions($version, $v) === 1)
				{
					$newVersion = $v;
				}
				else
				{
					break;
				}
			}
		}
		$methodName = $action . '_' . str_replace('.', '_', $version);
		$this->$methodName();

		$migration = CoreMigration::where('migration', '=', get_class($this))->first();
		if (!$migration)
		{
			$migration = new CoreMigration();
		}
		$migration->migration = get_class($this);
		$migration->version = $newVersion;

		$migration->save();
	}

	/**
	 * Sort the given array with versions
	 * @param string[] $versions
	 */
	public static function sortVersions(&$versions)
	{
		usort($versions, array(get_called_class(), 'compareVersions'));
	}

	/**
	 * Compare two versions against eachother
	 * @param $a
	 * @param $b
	 * @return int
	 */
	public static function compareVersions($a, $b)
	{
		$aExpl = explode('.', $a);
		$bExpl = explode('.', $b);

		$maxEl = max(array(count($aExpl), count($bExpl)));

		for ($i = 0; $i < $maxEl; ++$i)
		{
			if (isset($aExpl[$i]) && !isset($bExpl[$i]))
			{
				return 1;
			}
			elseif (!isset($aExpl[$i]) && isset($bExpl[$i]))
			{
				return -1;
			}
			elseif ($aExpl[$i] > $bExpl[$i])
			{
				return 1;
			}
			elseif ($aExpl[$i] < $bExpl[$i])
			{
				return -1;
			}
		}

		return 0;
	}

}
