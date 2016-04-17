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
 * Migration for userLocalization-table
 */
trait _000010CreateUserLocalizationsTable
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		app('db')->connection()->getSchemaBuilder()->create('user_localizations', function (Blueprint $table)
		{
			$table->integer('user_id')->unsigned();
			$table->primary('user_id');
			$table->string('locale', 10)->nullable();
			$table->string('currency', 10)->nullable();
			$table->string('timezone')->nullable();
			$table->string('measurement')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		app('db')->connection()->getSchemaBuilder()->drop('user_localizations');
	}
}
