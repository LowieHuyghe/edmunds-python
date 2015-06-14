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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LH\Core\Database\Seeders\PasswordResetsSeeder;

/**
 * Migration made for password_resets-table
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class PasswordResetsMigration extends BaseMigration
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->table = 'password_resets';
		parent::__construct(new PasswordResetsSeeder($this->table));
	}

	public function up_0_1()
	{
		Schema::create($this->table, function (Blueprint $table) {
			$table->string('email')->index();
			$table->string('token')->index();
			$table->timestamp('created_at');
		});
	}
	public function down_0_1()
	{
		Schema::drop($this->table);
	}
}