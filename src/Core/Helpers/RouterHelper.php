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

use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;

/**
 * The helper responsible for the routing
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class RouterHelper extends Controller
{
	/**
	 * Do the route logic
	 * @param string $route
	 * @return mixed
	 */
	public function route($route)
	{
		$response = $this->routeHandler($route);

		if (Config::get('app.routing.redirecthalt') && is_a($response, RedirectResponse::class))
		{
			$targetUrl = $response->getTargetUrl();
			return "Redirecting to: <a href='$targetUrl'>$targetUrl</a>";
		}

		return $response;
	}

	/**
	 * Do the route logic
	 * @param string $route
	 * @return mixed
	 */
	private function routeHandler($route)
	{
		$namespace = Config::get('app.routing.namespace');
		if (!$namespace)
		{
			throw new \Exception('app.routing.namespace not found in Config.');
			return;
		}
		$namespace = trim($namespace, "/");

		//Check if ajax-call
		$ajax = $this->getRouter()->getCurrentRequest()->ajax();

		//Get route and its parts
		$route = trim($route, "/");
		$parts = explode('/', $route);

		//Go throught all the parts back to front
		for ($i = count($parts)-1; $i >= 0; --$i)
		{
			//Make the classname
			$className = '';
			for ($j = 0; $j <= $i; ++$j)
			{
				$className .= '\\' . ucfirst($parts[$j]);
			}

			//Check if it exists
			$className = $namespace .  ucfirst($className) . ($ajax ? 'Ajax' : '') . 'Controller';

			if (class_exists($className))
			{
				if ($i == count($parts)-1)
				{
					//Index by default
					$methodName = 'index';
				}
				else
				{
					//Method name
					$methodName = strtolower($parts[$i + 1]);
				}

				//Make instance of controller
				$controller = new $className($this->getRouter());

				//Check if method exists
				if (method_exists($controller, $methodName))
				{
					//Get the variables
					$variables = array();
					for ($j = $i + 2; $j < count($parts); ++$j)
					{
						$variables[] = strtolower($parts[$j]);
					}

					//Check if number of given parameters equal the method
					if (count($variables) != (new \ReflectionMethod($className, $methodName))->getNumberOfRequiredParameters())
					{
						break;
					}

					//Check if authentication is needed
					$authCheckMethod = 'authenticationCheck';
					if (method_exists($controller, $authCheckMethod))
					{
						$authResponse = $controller->$authCheckMethod();
						if ($authResponse !== true)
						{
							return $authResponse;
						}
					}

					//Initialize
					$controller->initialize();

					//Call method with variables
					switch (count($variables))
					{
						case 0:
							return $controller->$methodName();
							break;
						case 1:
							return $controller->$methodName($variables[0]);
							break;
						case 2:
							return $controller->$methodName($variables[0], $variables[1]);
							break;
						case 3:
							return $controller->$methodName($variables[0], $variables[1], $variables[2]);
							break;
						case 4:
							return $controller->$methodName($variables[0], $variables[1], $variables[2], $variables[3]);
							break;
						case 5:
							return $controller->$methodName($variables[0], $variables[1], $variables[2], $variables[3], $variables[4]);
							break;
						case 6:
							return $controller->$methodName($variables[0], $variables[1], $variables[2], $variables[3], $variables[4], $variables[5]);
							break;
						case 7:
							return $controller->$methodName($variables[0], $variables[1], $variables[2], $variables[3], $variables[4], $variables[5], $variables[6]);
							break;
						case 8:
							return $controller->$methodName($variables[0], $variables[1], $variables[2], $variables[3], $variables[4], $variables[5], $variables[6], $variables[7]);
							break;
						case 9:
							return $controller->$methodName($variables[0], $variables[1], $variables[2], $variables[3], $variables[4], $variables[5], $variables[6], $variables[7], $variables[8]);
							break;
						case 10:
							return $controller->$methodName($variables[0], $variables[1], $variables[2], $variables[3], $variables[4], $variables[5], $variables[6], $variables[7], $variables[8], $variables[9]);
							break;
						default:
							//Too many arguments, abort
							return abort(404);
							break;
					}
				}
				else
				{
					//Didn't find method so dismiss
					break;
				}
			}
		}

		return abort(404);
	}
}
