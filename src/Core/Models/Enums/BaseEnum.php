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

namespace Core\Models\Enums;

use ReflectionClass;

/**
 * Enum base to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
abstract class BaseEnum
{

	private static function getConstants()
	{
		$reflect = new ReflectionClass(get_called_class());
		return $reflect->getConstants();
	}

	public static function isValidName($name, $strict = false)
	{
		$constants = self::getConstants();

		if ($strict) {
			return array_key_exists($name, $constants);
		}

		$keys = array_map('strtolower', array_keys($constants));
		return in_array(strtolower($name), $keys);
	}

	public static function isValidValue($value, $strict = false)
	{
		$values = array_values(self::getConstants());
		return in_array($value, $values, $strict);
	}

	public static function getName($value)
	{
		return array_search($value, self::getConstants());
	}

	public static function getValues()
	{
		return array_values(self::getConstants());
	}

	public static function getAll()
	{
		return self::getConstants();
	}

}
