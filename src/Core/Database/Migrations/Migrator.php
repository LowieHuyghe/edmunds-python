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

namespace Core\Database\Migrations;

/**
 * The core migrator
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Migrator extends \Illuminate\Database\Migrations\Migrator
{

	/**
	 * Get all of the migration files in a given path.
	 * @param  string  $path
	 * @return array
	 */
	public function getMigrationFiles($path)
	{
		$coreFiles = $this->files->glob(CORE_BASE_PATH . '/database/migrations/*_*.php');
		if ($coreFiles === false) $coreFiles = [];

		$appFiles = $this->files->glob($path.'/*_*.php');
		if ($appFiles === false) $appFiles = [];

		$files = array_merge($coreFiles, $appFiles);


		$files = array_map(function ($file) {
			return str_replace('.php', '', basename($file));

		}, $files);

		// Once we have all of the formatted file names we will sort them and since
		// they all start with a timestamp this should give us the migrations in
		// the order they were actually created by the application developers.
		sort($files);

		return $files;
	}

}
