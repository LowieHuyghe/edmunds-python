<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Database\Migrations\Traits;

use Illuminate\Database\Schema\Blueprint;

/**
 * Migration for enums-pivot-table
 */
trait CreateEnumsPivotTable
{
	/**
	 * The table used for pivot
	 * @var string
	 */
	//protected $table;

	/**
	 * The name for id of model
	 * @var string
	 */
	//protected $idModel;

	/**
	 * The name for id of enum
	 * @var string
	 */
	//protected $idEnum;

	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		app('db')->connection()->getSchemaBuilder()->create($this->table, function (Blueprint $table)
		{
			$table->integer($this->idModel)->unsigned();
			$table->integer($this->idEnum)->unsigned();
			$table->primary(array($this->idModel, $this->idEnum));
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		app('db')->connection()->getSchemaBuilder()->drop($this->table);
	}
}
