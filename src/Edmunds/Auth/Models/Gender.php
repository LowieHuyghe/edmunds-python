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

/**
 * The model for gender
 */
class Gender extends BaseEnumModel
{
	const	MALE	= 1,
			FEMALE	= 2;
}
