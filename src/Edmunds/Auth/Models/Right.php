<?php

/**
 * Edmunds
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 */

namespace Edmunds\Auth\Models;
use Edmunds\Bases\Models\BaseEnumModel;
use Edmunds\Database\Relations\BelongsToManyEnums;

/**
 * The model for rights
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
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
