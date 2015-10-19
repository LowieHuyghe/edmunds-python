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

namespace Core\Models;
use Core\Bases\Models\BaseEnumModel;
use Core\Database\Relations\BelongsToManyEnums;

/**
 * The model for rights
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Right extends BaseEnumModel
{
	/**
	 * The class responsible for the roles
	 * @var string
	 */
	protected $roleClass;

	/**
	 * Roles belonging to this right
	 * @return BelongsToManyEnums
	 * @throws \Exception
	 */
	public function roles()
	{
		if (!isset($this->roleClass))
		{
			throw new \Exception('The class representing the Roles not set');
		}
		return $this->belongsToManyEnums($this->roleClass, 'role_rights');
	}
}
