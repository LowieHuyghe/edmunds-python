<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

namespace Edmunds\Database\Migrations\Traits;

use Illuminate\Database\Schema\Blueprint;

/**
 * Migration for enums-pivot-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
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
