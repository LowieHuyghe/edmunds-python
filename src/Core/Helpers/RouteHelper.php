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

use Core\Exceptions\ConfigNotFoundException;
use Core\Structures\Client\Input;
use Core\Structures\Client\Visitor;
use Core\Structures\Http\Request;
use Core\Structures\Http\Response;
use Core\Structures\Client\Session;
use Core\Exceptions\AbortException;
use Laravel\Lumen\Routing\Controller;

/**
 * The helper responsible for the routing
 * To use it, just add the following to routes.php:
 *
	Route::any('{all}', [
		'uses' => '\Core\Helpers\RouteHelper@route'
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
	 * @var string
	 */
	private $route;

	/**
	 * Do the route logic
	 * @param \Illuminate\Http\Request $request
	 * @param string $route
	 * @return mixed
	 */
	public function route(\Illuminate\Http\Request $request, $route)
	{
		//Initialize stuff
		Session::initialize($request->session());
		$request->setSession(Session::current());
		Request::initialize($request);

		$this->route = $route;

		$this->checkConfig();

		$response = null;
		try
		{
			$response = $this->routeHandler();
		}
		catch (AbortException $ex)
		{
			if ($ex->status)
			{
				abort($ex->status, $ex->getMessage());
			}
		}
		return $response;
	}

	/**
	 * Do the route logic
	 * @return \Illuminate\Http\Response
	 */
	private function routeHandler()
	{
		//Check if it is a valid route
		if (!$this->isValidRoute())
		{
			throw new AbortException(404);
		}

		//Get all the constants
		list($namespace, $defaultControllerName, $homeControllerName, $requestMethod, $requestType, $segments) = $this->getAllConstants();

		//Get the controller and make instance of defaultController
		list($controllerName, $remainingSegments) = $this->getController($namespace, $defaultControllerName, $homeControllerName, $requestType, $segments);
		if (!$controllerName)
		{
			throw new AbortException(404);
		}
		$this->prepareController($controllerName);

		//Get the name of the method
		list($methodName, $parameterSpecs, $parameters, $requiredRights) = $this->getMethodName($controllerName, $requestMethod, $remainingSegments);
		if (!$methodName
			|| !$this->areParametersValid($parameterSpecs, $parameters))
		{
			throw new AbortException(404);
		}

		//Call the method
		return $this->callMethod($defaultControllerName, $controllerName, $methodName, $parameters, $requiredRights);
	}

	/**
	 * Validate the route
	 * @return bool
	 */
	private function isValidRoute()
	{
		//Only let the accepted routes through
		return (preg_match('/^[\w\d\/$\-_\.\+!\*]*$/', Request::current()->route) === 1);
	}

	/**
	 * Get all the constants to do the routing
	 * @return array
	 */
	private function getAllConstants()
	{
		//Fetch namespace
		$namespace = config('app.routing.namespace');
		$namespace = trim($namespace, '\\');
		//Fetch defaultController
		$defaultController = config('app.routing.default');
		$defaultController = $namespace . '\\' . $defaultController;
		//Fetch homeController
		$homeController = config('app.routing.home');
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
			Response::current()->responseJson();
		}
		elseif (Request::current()->json || (Input::current()->has('output') && strtolower(Input::current()->get('output')) == 'json'))
		{
			$requestType = 'json';
			//Set default response to json
			Response::current()->responseJson();
		}
		elseif (Input::current()->has('output') && strtolower(Input::current()->get('output')) == 'xml')
		{
			$requestType = 'xml';
			//Set default response to xml
			Response::current()->responseXml();
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
		$requiredRights = null;
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
					//Fetch rights from array
					$requiredRights = $routeMethodNameOptions['r'];
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
			$requiredRights = $rootMethodOptions['r'];
		}

		//Check if parameter-count is right
		if (count($parameterSpecs) != count($remainingSegments))
		{
			return array(null, null, null, null);
		}

		//Return the right method
		return array($methodName, $parameterSpecs, $remainingSegments, $requiredRights);
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
	 * @param array $requiredRights
	 * @return \Illuminate\Http\Response
	 */
	private function callMethod($defaultControllerName, $controllerName, $methodName, $parameters, $requiredRights)
	{
		//Set the rights required
		Visitor::$requiredRights = array_unique($requiredRights);

		//Initialize the controllers
		$controller = app($controllerName);
		$defaultController = app($defaultControllerName);

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
				$response = $controller->$methodName();
				break;
			case 1:
				$response = $controller->$methodName($parameters[0]);
				break;
			case 2:
				$response = $controller->$methodName($parameters[0], $parameters[1]);
				break;
			case 3:
				$response = $controller->$methodName($parameters[0], $parameters[1], $parameters[2]);
				break;
			case 4:
				$response = $controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3]);
				break;
			case 5:
				$response = $controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4]);
				break;
			case 6:
				$response = $controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5]);
				break;
			case 7:
				$response = $controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5], $parameters[6]);
				break;
			case 8:
				$response = $controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5], $parameters[6], $parameters[7]);
				break;
			case 9:
				$response = $controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5], $parameters[6], $parameters[7], $parameters[8]);
				break;
			case 10:
				$response = $controller->$methodName($parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4], $parameters[5], $parameters[6], $parameters[7], $parameters[8], $parameters[9]);
				break;
			default:
				$response = Response::current()->response404();
				break;
		}

		//set the status of the response
		Response::current()->assign('success', ($response !== false));

		//PostRender
		$controller->postRender();

		//Return response
		return Response::current()->getResponse();
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
			if (!config($line))
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
