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
 * Migration for userLocations-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class CreateUserLocationsTable extends Migration
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		app('db')->connection()->getSchemaBuilder()->create('user_locations', function (Blueprint $table)
		{
			$table->integer('user_id')->unsigned();
			$table->primary('user_id');
			$table->string('ip');

			$table->string('continent_code', 10)->nullable();
			$table->string('continent_name')->nullable();

			$table->string('country_code', 10)->nullable();
			$table->string('country_name')->nullable();

			$table->string('region_code', 10)->nullable();
			$table->string('region_name')->nullable();

			$table->string('city_name')->nullable();
			$table->string('postal_code', 32)->nullable();

			$table->float('latitude')->nullable();
			$table->float('longitude')->nullable();
			$table->string('timezone')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		app('db')->connection()->getSchemaBuilder()->drop('user_locations');
	}
}
