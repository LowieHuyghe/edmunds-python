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

/**
 * Migration for user-roles-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
trait _0003_CreateUserRolesTable
{
	use _0000_CreateEnumsPivotTable;

	/**
	 * The table used for pivot
	 * @var string
	 */
	protected $table = 'user_roles';

	/**
	 * The name for id of model
	 * @var string
	 */
	protected $idModel = 'user_id';

	/**
	 * The name for id of enum
	 * @var string
	 */
	protected $idEnum = 'role_id';
}
