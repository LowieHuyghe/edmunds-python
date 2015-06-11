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
use League\Flysystem\Exception;

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
	 * Constructor
	 * @throws Exception
	 */
	function __construct()
	{
		$upVersions = self::getVersions('up');
		$downVersions = self::getVersions('down');

		if (count($upVersions) == count($downVersions))
		{
			throw new Exception("Migration class 'up' and 'down' functions are not equal.");
		}
	}

	/**
	 * Run the migration of a certain version
	 * @param string $version
	 */
	public function up($newVersion)
	{
		$versions = self::getVersions();

		foreach ($versions as $version)
		{

		}

	}

	/**
	 * Reverse the migrations.
	 * @param string $version
	 */
	public function down($version)
	{
		$versions = self::getVersions();
	}

	/**
	 * Get the different versions
	 * @param string $prefix
	 * @return array
	 */
	public function getVersions($prefix = 'up')
	{
		$methods = get_class_methods(get_class($this));

		//Only the methods with that prefix
		$methods = array_filter($methods, function($el) use ($prefix) {
			return strpos($el, $prefix . '_') === 0;
		});

		//Convert to . syntax
		$versions = array_map(function($el) use ($prefix) {
			$el = str_replace($prefix . '_', '', $el);
			return implode('.', explode('_', $el));
		}, $methods);

		$result = array_sort($versions, function($el1, $el2) {

		});

		return $result;
	}

}
