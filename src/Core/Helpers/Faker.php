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

namespace Core\Helpers;

use Faker\Factory;
use Faker\Generator;

/**
 * The helper for creating dummy text
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Faker extends BaseHelper
{
	/**
	 * @var Generator
	 */
	private static $generator;

	/**
	 * Fetch a generator
	 * @return Generator
	 */
	private static function getGenerator()
	{
		if (!isset(self::$generator))
		{
			self::$generator = Factory::create();
		}
		return self::$generator;
	}

	/**
	 * Return real text
	 * @param int $maxNbChars
	 * @param int $indexSize
	 * @return string
	 */
	public static function text($maxNbChars = 200, $indexSize = 2)
	{
		$generator = self::getGenerator();
		return $generator->realText($maxNbChars, $indexSize);
	}

	/**
	 * Return lorem ipsum
	 * @param int $maxNbChars
	 * @return string
	 */
	public static function ipsum($maxNbChars = 200)
	{
		$generator = self::getGenerator();
		return $generator->text($maxNbChars);
	}

}
