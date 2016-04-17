<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 */

namespace Edmunds\Database\Migrations;

use Edmunds\Database\Migrations\Traits\CreateEnumsPivotTable;

/**
 * Migration for user-roles-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 */
trait _000003CreateUserRolesTable
{
	use CreateEnumsPivotTable;

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
