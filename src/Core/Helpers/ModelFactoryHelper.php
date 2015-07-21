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

namespace LH\Core\Helpers;

use Illuminate\Database\Eloquent\Factory;
use Mockery\CountValidator\Exception;

/**
 * The helper responsible for creating objects
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class ModelFactoryHelper extends BaseHelper
{
	/**
	 * Keep track of which classes were already defined
	 * @var array
	 */
	private static $defined = array();

	/**
	 * Get a instance of a certain model
	 * @param string $className
	 * @param function(Factory) $defineFunction
	 * @param array $attributes
	 * @return mixed
	 */
	public static function createModel($className, $defineFunction, $attributes = array())
	{
		if (!isset(self::$defined[$className]))
		{
			self::$defined[$className] = array('defined' => false, 'function' => $defineFunction);
		}

		try
		{
			$result = factory($className)->make($attributes);
			return $result;
		}
		catch (\InvalidArgumentException $e)
		{
			//Check if factories are not defined
			if (strpos($e->getMessage(), 'Unable to locate factory with name') === 0)
			{
				throw new Exception("Factory not defined. Add '\\LH\\Core\\Helpers\\ModelFactoryHelper::defineModels(\$factory);' to '{project}/database/factories/ModelFactory.php'.");
			}
			else
			{
				throw $e;
			}
		}
	}

	/**
	 * Define all the models
	 * @param Factory $factory
	 */
	public static function defineModels($factory)
	{
		foreach (self::$defined as $className => &$array)
		{
			if (!$array['defined'])
			{
				$array['defined'] = true;
				$factory->define($className, $array['function']);
			}
		}

	}

}
