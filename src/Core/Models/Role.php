<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Models;

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
	 * The class responsible for the rights
	 * @var string
	 */
	protected $rightClass;

	/**
	 * Rights belonging to this role
	 * @return BelongsToManyEnums
	 */
	public function rights()
	{
		if (!isset($this->rightClass))
		{
			throw new Exception('The class representing the Rights not set');
		}
		return $this->belongsToManyEnums($this->rightClass, 'role_rights');
	}
}
