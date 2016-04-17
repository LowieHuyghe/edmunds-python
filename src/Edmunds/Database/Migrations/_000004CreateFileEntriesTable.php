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
 * Migration for users-table
 */
trait _000004CreateFileEntriesTable
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
