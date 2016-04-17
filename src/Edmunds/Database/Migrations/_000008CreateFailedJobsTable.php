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
trait _000008CreateFailedJobsTable
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		app('db')->connection()->getSchemaBuilder()->create('failed_jobs', function (Blueprint $table) {
			$table->increments('id');
			$table->text('connection');
			$table->text('queue');
			$table->longText('payload');
			$table->timestamp('failed_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		app('db')->connection()->getSchemaBuilder()->drop('failed_jobs');
	}
}
