<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Auth\Models;

use Edmunds\Bases\Models\BaseEnumModel;
use Edmunds\Database\Relations\BelongsToManyEnums;

/**
 * The model for rights
 */
class Right extends BaseEnumModel
{
	/**
	 * Roles belonging to this right
	 * @return BelongsToManyEnums
	 * @throws \Exception
	 */
	public function roles()
	{
		return $this->belongsToManyEnums(config('app.auth.models.role'), 'role_rights');
	}
}
