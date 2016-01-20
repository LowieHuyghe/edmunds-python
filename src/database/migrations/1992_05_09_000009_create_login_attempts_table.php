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
 * Migration for roleRights-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
class CreateLoginAttemptsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		app('db')->connection()->getSchemaBuilder()->create('login_attempts', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('ip');
			$table->string('type');

			$table->integer('user_id')->unsigned()->nullable();
			$table->string('email')->nullable();
			$table->string('pass')->nullable();

			$table->timestamps();

			$table->index(array('ip', 'created_at'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		app('db')->connection()->getSchemaBuilder()->drop('login_attempts');
	}
}
