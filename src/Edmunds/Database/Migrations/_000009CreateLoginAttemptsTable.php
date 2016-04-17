<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Database\Migrations;

use Illuminate\Database\Schema\Blueprint;

/**
 * Migration for roleRights-table
 */
trait _000009CreateLoginAttemptsTable
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
			$table->string('token')->nullable();

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
