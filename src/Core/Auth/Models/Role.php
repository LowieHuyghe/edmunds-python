<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace Core\Auth\Models;

use Core\Bases\Models\BaseEnumModel;
use Core\Database\Relations\BelongsToManyEnums;

/**
 * The model for roles
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Role extends BaseEnumModel
{
	/**
	 * Rights belonging to this role
	 * @return BelongsToManyEnums
	 * @throws \Exception
	 */
	public function rights()
	{
		return $this->belongsToManyEnums(config('app.auth.models.right'), 'role_rights');
	}
}
