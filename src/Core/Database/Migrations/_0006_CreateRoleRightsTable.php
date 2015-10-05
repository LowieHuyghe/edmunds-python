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

namespace Core\Database\Migrations;

/**
 * Migration for roleRights-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
trait _0006_CreateRoleRightsTable
{
	use _0000_CreateEnumsPivotTable;

	/**
	 * The table used for pivot
	 * @var string
	 */
	protected $table = 'role_rights';

	/**
	 * The name for id of model
	 * @var string
	 */
	protected $idModel = 'role_id';

	/**
	 * The name for id of enum
	 * @var string
	 */
	protected $idEnum = 'right_id';
}
