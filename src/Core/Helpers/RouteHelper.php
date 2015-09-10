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

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use LH\Core\Exceptions\ConfigNotFoundException;

/**
 * The helper responsible for the routing
 * To use it, just add the following to routes.php:
 *
	Route::any('{all}', [
		'uses' => '\LH\Core\Helpers\RouteHelper@route'
	])->where('all', '.*');
 *
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class RouteHelper extends Controller
{
	/**
	 * Do the route logic
	 * @param string $route
	 * @return mixed
	 */
	public function route($route)
	{
		$this->checkConfig();

		$response = $this->routeHandler($route);

		return $response;
	}

	/**
	 * Do the route logic
	 * @param string $route
	 * @return mixed
	 */
	private function routeHandler($route)
	{
		//Check if it is a valid route
		if (!$this->isValidRoute($route))
		{
			return abort(404);
		}

		//Get all the constants
		list($namespace, $defaultControllerName, $homeControllerName, $requestMethod, $requestType, $segments) = $this->getAllConstants();

		//Get the controller and make instance of defaultController
		list($controllerName, $remainingSegments) = $this->getController($namespace, $defaultControllerName, $homeControllerName, $requestType, $segments);
		if (!$controllerName)
		{
			return abort(404);
		}
		$this->prepareController($controllerName);

		//Get the name of the method
		list($methodName, $parameterSpecs, $parameters, $requiredRoles) = $this->getMethodName($controllerName, $requestMethod, $remainingSegments);
		if (!$methodName
			|| !$this->areParametersValid($parameterSpecs, $parameters))
		{
			return abort(404);
		}

		//Call the method
		return $this->callMethod($defaultControllerName, $controllerName, $methodName, $parameters, $requiredRoles);
	}

	/**
	 * Validate the route
	 * @param string $route
	 * @return bool
	 */
	private function isValidRoute($route)
	{
		//Only let the accepted routes through
		return (preg_match('/^[\w\d\/$\-_\.\+!\*]*$/', $route) === 1);
	}

	/**
	 * Get all the constants to do the routing
	 * @return array
	 */
	private function getAllConstants()
	{
		//Fetch namespace
		$namespace = Config::get('app.routing.namespace');
		$namespace = trim($namespace, '\\');
		//Fetch defaultController
		$defaultController = Config::get('app.routing.default');
		$defaultController = $namespace . '\\' . $defaultController;
		//Fetch homeController
		$homeController = Config::get('app.routing.home');
		$homeController = $namespace . '\\' . $homeController;

		//Get the current request
		$request = $this->getRouter()->getCurrentRequest();
		//Get the call-method
		$requestMethod = strtolower($request->method());
		if ($requestMethod == 'patch')
		{
			$requestMethod = 'put';
		}
		$requestType = null;
		//Check if ajax-call
		if ($request->ajax())
		{
			$requestType = 'ajax';
		}
		elseif ($request->wantsJson() || (InputHelper::getInstance()->has('output') && strtolower(InputHelper::getInstance()->get('output')) == 'json'))
		{
			$requestType = 'json';
		}
		elseif ($request->accepts(array('application/xml', 'text/xml')) || InputHelper::getInstance()->has('output') && strtolower(InputHelper::getInstance()->get('output')) == 'xml')
		{
			$requestType = 'xml';
		}
		//Get route and its parts
		$segments = $request->segments();

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
	 * Prepare the controller
	 * @param string $controllerName
	 */
	private function prepareController($controllerName)
	{
		$routeMethods = $controllerName::getRouteMethods();

		//Preparing the routeMethods
		if (!isset($routeMethods[0]))
		{
			$routeMethods[0] = array();
		}
		foreach ($routeMethods as $key => $value)
		{
			if (!is_numeric($key))
			{
				$routeMethods[0][$key] = $value;
				unset($routeMethods[$key]);
			}
		}

		//Preparing each route of the routeMethods

		foreach ($routeMethods as $uriPosition => $v)
		{
			$routeMethodUriSpecs = &$routeMethods[$uriPosition];
			foreach ($routeMethodUriSpecs as $route => &$routeMethodRouteSpecs)
			{
				//Method
				if (!isset($routeMethodRouteSpecs['m']))
				{
					$routeMethodRouteSpecs['m'] = array('get');
				}
				elseif (!is_array($routeMethodRouteSpecs['m']))
				{
					$routeMethodRouteSpecs['m'] = array($routeMethodRouteSpecs['m']);
				}

				//Parameters
				if (!isset($routeMethodRouteSpecs['p']))
				{
					$routeMethodRouteSpecs['p'] = array();
				}

				//Roles
				if (!isset($routeMethodRouteSpecs['r']))
				{
					$routeMethodRouteSpecs['r'] = array();
				}
				elseif (!is_array($routeMethodRouteSpecs['r']))
				{
					$routeMethodRouteSpecs['r'] = array($routeMethodRouteSpecs['r']);
				}
			}
		}

		$controllerName::setRouteMethods($routeMethods);
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
	private function getMethodName($controllerName, $requestMethod, $remainingSegments)
	{
		$routeMethods = $controllerName::getRouteMethods();

		//Check if it is index-page and otherwise filter it out
		if (empty($remainingSegments))
		{
			if ($requestMethod == 'get' && isset($routeMethods[0]['getIndex']))
			{
				return array($requestMethod .'Index', array(), array(), $routeMethods[0]['getIndex']['r']);
			}
			return array(null, null, null, null);
		}
		elseif (isset($routeMethods[0]['getIndex']))
		{
			unset($routeMethods[0]['getIndex']);
		}

		//If root methods are enabled, fetch them but give priority to other methods
		$rootMethodName = null;
		$rootMethodOptions = null;
		foreach (array('get', 'post', 'put', 'delete') as $name)
		{
			if (isset($routeMethods[0][$name]))
			{
				if ($requestMethod == $name)
				{
					$rootMethodName = $name;
					$rootMethodOptions = $routeMethods[0][$name];
				}
				unset($routeMethods[0][$name]);
			}
		}

		$methodName = null;
		$parameterSpecs = array();
		$requiredRoles = null;
		//Looping through all the different options for this controller
		for ($uriPosition = 0; $uriPosition < count($remainingSegments); ++$uriPosition)
		{
			if (!isset($routeMethods[$uriPosition]))
			{
				continue;
			}
			$controllerMethodsSpecs = $routeMethods[$uriPosition];

			//2 => array( 'home' => array('get') )
			foreach ($controllerMethodsSpecs as $controllerMethodName => $routeMethodNameOptions)
			{
				if (strtolower($controllerMethodName) == strtolower($requestMethod . $remainingSegments[$uriPosition]))
				{
					$methodName = $controllerMethodName;
					//Fetch parameter-count from array
					$parameterSpecs = $routeMethodNameOptions['p'];
					//Fetch roles from array
					$requiredRoles = $routeMethodNameOptions['r'];
					//Remove the methodName from the segments
					array_splice($remainingSegments, $uriPosition, 1);
					break;
				}
			}
		}

		//Check rootMethod option
		if (!$methodName && $rootMethodName)
		{
			$methodName = $rootMethodName;
			$parameterSpecs = $rootMethodOptions['p'];
			$requiredRoles = $rootMethodOptions['r'];
		}

		//Check if parameter-count is right
		if (count($parameterSpecs) != count($remainingSegments))
		{
			return array(null, null, null, null);
		}

		//Return the right method
		return array($methodName, $parameterSpecs, $remainingSegments, $requiredRoles);
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

	/**
	 * Call the method of the controller and return the response
	 * @param string $defaultControllerName
	 * @param string $controllerName
	 * @param string $methodName
	 * @param array $parameters
	 * @param array $requiredRoles
	 * @return mixed
	 */
	private function callMethod($defaultControllerName, $controllerName, $methodName, $parameters, $requiredRoles)
	{
		//Set the roles required
		VisitorHelper::$requiredRoles = $requiredRoles;

		//Initialize the controllers
		$controller = App::make($controllerName);
		$defaultController = App::make($defaultControllerName);

		//Initialize of defaultController
		$defaultController->initialize();

		//Initialize
		$controller->initialize();

		//PreRender
		$controller->preRender();

		$response = null;
		//Call method with variables
		switch (count($parameters))
		{
			case 0:
				$controller->$methodName();
				break;
			case 1:
				$controller->$methodName($parameters[0]);
				break;
			case 2:
				$controller->$methodName($parameters[0], $parameters[1]);
				break;
			case 3:
				$controller->$methodName($parameters[0], $parameters[1], $parameters[2]);
				break;
			case 4:
				$controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3]);
				break;
			case 5:
				$controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4]);
				break;
			case 6:
				$controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5]);
				break;
			case 7:
				$controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5], $parameters[6]);
				break;
			case 8:
				$controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5], $parameters[6], $parameters[7]);
				break;
			case 9:
				$controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5], $parameters[6], $parameters[7], $parameters[8]);
				break;
			case 10:
				$controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5], $parameters[6], $parameters[7], $parameters[8], $parameters[9]);
				break;
			default:
				ResponseHelper::getInstance()->response404();
				break;
		}

		//PostRender
		$controller->postRender();

		//Return response
		return ResponseHelper::getInstance()->getResponse();
	}

	/**
	 * Check if all configuration is in place
	 * @throws \Exception
	 */
	private function checkConfig()
	{
		//Fetch the configuration
		$config = ConfigHelper::get('core.config.required');

		//Check if everything is in place
		$notFound = array();
		foreach ($config as $line)
		{
			if (!Config::get($line))
			{
				$notFound[] = $line;
			}
		}

		//Throw error if needed
		if (!empty($notFound))
		{
			throw new ConfigNotFoundException($notFound);
		}
	}
}
