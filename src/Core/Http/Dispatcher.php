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

namespace Core\Http;

use Core\Http\Client\Input;
use Core\Http\Client\Visitor;
use Core\Http\Client\Session;
use Illuminate\View\View;

/**
 * The helper responsible for the routing
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Dispatcher implements \FastRoute\Dispatcher
{
	/**
	 * Dispatches against the provided HTTP method verb and URI.
	 *
	 * Returns array with one of the following formats:
	 *
	 *     [self::NOT_FOUND]
	 *     [self::METHOD_NOT_ALLOWED, ['GET', 'OTHER_ALLOWED_METHODS']]
	 *     [self::FOUND, $handler, ['varName' => 'value', ...]]
	 *
	 * @param string $httpMethod
	 * @param string $uri
	 *
	 * @return array
	 */
	public function dispatch($httpMethod, $uri)
	{
		//Get all the constants
		list($namespace, $defaultControllerName, $homeControllerName, $requestMethod, $requestType, $segments) = $this->getAllConstants();

		//Get the controller and make instance of defaultController
		list($controllerName, $remainingSegments) = $this->getController($namespace, $defaultControllerName, $homeControllerName, $requestType, $segments);

		//Get the name of the method
		if ($controllerName)
		{
			list($route, $parameters) = $this->getRoute($controllerName, $requestMethod, $remainingSegments);
			if ($route && $this->areParametersValid($route->parameters, $parameters))
			{
				//Prepare result
				$routeResults = array(
					self::FOUND,
					array(
						'uses' => '\\' . $controllerName . '@responseFlow',
					),
					array(
						$defaultControllerName,
						$route,
						$parameters,
					),
				);

				//Middleware
				$middleware = $route->middleware;
				if ($route->rights)
				{
					$middleware[] = 'auth';
					$middleware[] = 'rights';
				}
				if ($middleware)
				{
					$routeResults[1]['middleware'] = $middleware;
				}

				//Return result
				return $routeResults;
			}
		}

		//No route found
		return array(
			self::NOT_FOUND,
			array(),
			array(),
		);
	}

	/**
	 * Get all the constants to do the routing
	 * @return array
	 */
	private function getAllConstants()
	{
		//Fetch namespace
		$namespace = env('ROUTING_NAMESPACE');
		$namespace = trim($namespace, '\\');
		//Fetch defaultController
		$defaultController = env('ROUTING_DEFAULTCONTROLLER');
		$defaultController = $namespace . '\\' . $defaultController;
		//Fetch homeController
		$homeController = env('ROUTING_HOMECONTROLLER');
		$homeController = $namespace . '\\' . $homeController;

		//Get the call-method
		$requestMethod = strtolower(Request::current()->method);
		if ($requestMethod == 'patch')
		{
			$requestMethod = 'put';
		}
		$requestType = null;
		//Check if ajax-call
		if (Request::current()->ajax)
		{
			$requestType = 'ajax';
			//Set default response to ajax
			Response::current()->setType(Response::TYPE_JSON);
		}
		elseif (Request::current()->json || (Input::current()->has('output') && strtolower(Input::current()->get('output')) == 'json'))
		{
			$requestType = 'json';
			//Set default response to json
			Response::current()->setType(Response::TYPE_JSON);
		}
		elseif (Input::current()->has('output') && strtolower(Input::current()->get('output')) == 'xml')
		{
			$requestType = 'xml';
			//Set default response to xml
			Response::current()->setType(Response::TYPE_XML);
		}
		//Get route and its parts
		$segments = Request::current()->segments;

		return array($namespace, $defaultController, $homeController, $requestMethod, $requestType, $segments);
	}

	/**
	 * Get the controller from the input
	 * @param string $namespace
	 * @param string $defaultControllerName
	 * @param string $homeControllerName
	 * @param string $requestType
	 * @param array $segments
	 * @return array
	 */
	private function getController($namespace, $defaultControllerName, $homeControllerName, $requestType, $segments)
	{
		//Go through all the parts back to front
		$controllerLoopCount = min(3, count($segments));
		for ($i = 0; $i <= $controllerLoopCount; ++$i)
		{
			//Make the className
			if ($i == $controllerLoopCount)
			{
				//HomeController
				$className = $homeControllerName;
			}
			else
			{
				//Form class-name
				$className = '';
				for ($j = 0; $j <= $i; ++$j)
				{
					$className .= '\\' . ucfirst($segments[$j]);
				}
				$className = $namespace . $className . ($requestType ? ucfirst($requestType) : '') . 'Controller';

				//Home controller only approachable when called from '/'
				//Default controller not approachable
				if (in_array($className, array($homeControllerName, $defaultControllerName)))
				{
					break;
				}
			}

			//Check if it is valid and exists
			if ($this->isValidClass($className))
			{
				//Get the remaining segments
				$remainingSegments = array_slice($segments, ($i == $controllerLoopCount ? 0 : $i + 1));
				return array($className, $remainingSegments);
			}
		}

		return array(null, null);
	}

	/**
	 * Validate the className and check if it exists
	 * @param string $className
	 * @return bool
	 */
	private function isValidClass($className)
	{
		return (
			preg_match('/^[\w\\\\]*$/', $className) === 1
			&& class_exists($className)
		);
	}

	/**
	 * Get the method name
	 * @param string $controllerName
	 * @param string $requestMethod
	 * @param array $remainingSegments
	 * @return array
	 */
	private function getRoute($controllerName, $requestMethod, $remainingSegments)
	{
		$routes = collect(call_user_func(array($controllerName, 'getRoutes')));

		//Check if it is index-page and otherwise filter it out
		if (empty($remainingSegments))
		{
			if ($requestMethod == 'get' && $route = $routes->where('name', 'getIndex')->first())
			{
				return array($route, $remainingSegments);
			}
			return array(null, null);
		}
		elseif ($keys = $routes->where('name', 'getIndex')->keys())
		{
			foreach ($keys as $key) $routes->forget($key);
		}

		//If root methods are enabled, fetch them but give priority to other methods
		$rootRoute = null;
		foreach (array('get', 'post', 'put', 'delete') as $name)
		{
			if ($keys = $routes->where('name', $name)->keys())
			{
				if ($requestMethod == $name)
				{
					$rootRoute = $routes->get($keys[0]);
				}
				foreach ($keys as $key) $routes->forget($key);
			}
		}

		$currentRoute = null;
		//Looping through all the different options for this controller
		for ($namePosition = 0; $namePosition < count($remainingSegments); ++$namePosition)
		{
			$routesForPosition = $routes->where('namePosition', $namePosition);
			if (!$routesForPosition->count())
			{
				continue;
			}

			$routesForPosition->each(function (Route $route) use ($requestMethod, &$remainingSegments, $namePosition, &$currentRoute)
			{
				if (strtolower($route->name) == strtolower($requestMethod . $remainingSegments[$namePosition]))
				{
					$currentRoute = $route;
					//Remove the methodName from the segments
					array_splice($remainingSegments, $namePosition, 1);
					return false;
				}
			});
		}

		//Check rootMethod option
		if (!$currentRoute && $rootRoute)
		{
			$currentRoute = $rootRoute;
		}

		//Check if parameter-count is right
		if (count($currentRoute->parameters) != count($remainingSegments))
		{
			return array(null, null);
		}

		//Return the right method
		return array($currentRoute, $remainingSegments);
	}

	/**
	 * @param array $parameterSpecs
	 * @param array $parameters
	 * @return bool
	 */
	private function areParametersValid($parameterSpecs, $parameters)
	{
		for ($i = 0; $i < count($parameters); ++$i)
		{
			if (preg_match('/^'.$parameterSpecs[$i].'$/', $parameters[$i]) !== 1)
			{
				return false;
			}
		}

		return true;
	}
}
