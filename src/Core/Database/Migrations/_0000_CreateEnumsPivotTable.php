<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

namespace LH\Core\Database\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for enums-pivot-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
trait _0000_CreateEnumsPivotTable
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
		Schema::create($this->table, function (Blueprint $table)
		{
			$table->integer($this->idModel)->unsigned();
			$table->integer($this->idEnum)->unsigned();
			$table->primary(array($this->idModel, $this->idEnum));
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		Schema::drop($this->table);
	}
}
