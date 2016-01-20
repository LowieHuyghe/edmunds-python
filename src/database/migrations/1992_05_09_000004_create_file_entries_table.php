<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

use Illuminate\Database\Migrations\Migration;

/**
 * Migration for users-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class CreateFileEntriesTable extends Migration
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		app('db')->connection()->getSchemaBuilder()->create('file_entries', function (Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 20)->unique();
			$table->string('md5', 32);
			$table->string('sha1', 40);
			$table->string('original_name');
			$table->string('mime', 20);
			$table->integer('type');
			$table->integer('size');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		app('db')->connection()->getSchemaBuilder()->drop('file_entries');
	}
}
