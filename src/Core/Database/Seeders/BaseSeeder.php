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

namespace LH\Core\Database\Seeders;

use Illuminate\Support\Facades\DB;

/**
 * Seeder base to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class BaseSeeder
{
	/**
	 * Table for deleting the records
	 * @var string
	 */
	protected $table;

	/**
	 * Constructor
	 * @param string $table
	 */
	function __construct($table)
	{
		$this->table = $table;
	}

	/**
	 * Delete the records
	 */
	private function delete()
	{
		DB::table($this->table)->delete();
	}

	/**
	 * Fill the table
	 * @param string $version
	 * @return bool Seeded
	 */
	public function fill($version)
	{
		$methodName = 'fill_' . str_replace('.', '_', $version);

		if (method_exists($this, $methodName))
		{
			//Delete current records
			$this->delete();

			//Run update
			$this->$methodName();

			return true;
		}

		return false;
	}

}
