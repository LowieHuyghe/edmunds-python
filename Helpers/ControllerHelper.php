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
use Core\BaseController;

/**
 * The helper to get controllers
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class ControllerHelper extends BaseHelper
{

	/**
	 * An array that contains all the controller-instances
	 * @var array
	 */
	private static $controllers = array();

	/**
	 * Return an instance of a controller
	 * @param $controllerName
	 * @return BaseController
	 */
	public static function get($controllerName)
	{
		if (!isset(self::$controllers[$controllerName]))
		{
			self::$controllers[$controllerName] = new $controllerName();
		}

		return self::$controllers[$controllerName];
	}
}
