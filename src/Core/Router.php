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

namespace Core;

use Core\Exceptions\AbortException;
use Core\Structures\Client\Input;
use Core\Structures\Client\Visitor;
use Core\Structures\Http\Request;
use Core\Structures\Http\Response;
use Core\Structures\Client\Session;
use Core\Structures\Http\Route;
use Illuminate\View\View;
use Laravel\Lumen\Routing\Controller;

/**
 * The helper responsible for the routing
 * To use it, just add the following to routes.php:
 *
	Route::any('{all}', [
		'uses' => '\Core\Controllers\Router@route'
	])->where('all', '.*');
 *
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Router extends Controller
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
	 * @throws ConfigNotFoundException
	 * @throws Exception
	 * @throws \Exception
	 */
	public function route(\Illuminate\Http\Request $request, $route)
	{
		//Initialize stuff
		Session::initialize($request->session());
		$request->setSession(Session::current());
		Request::initialize($request);

		$this->route = $route;

		$response = null;
		try
		{
			$response = $this->routeHandler();
		}
		catch (AbortException $ex)
		{
			$response = Response::current()->getResponse();
			if ($response->getStatusCode() >= 400)
			{
				$originalContent = $response->getOriginalContent();
				if ($originalContent instanceof View)
				{
					//TODO @Lowie Logging!
					$message = $originalContent->getData()['message'];
					if (app()->isLocal() && env('APP_DEBUG'))
					{
						abort($response->getStatusCode(), $message, $response->headers->all());
					}
				}
				else
				{
					//TODO @Lowie Logging!
					abort($response->getStatusCode(), $originalContent, $response->headers->all());
				}
			}
		}
		catch (Exception $ex)
		{
			//TODO @Lowie Logging!
			throw $ex;
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
			Response::current()->response404();
		}
		elseif (app()->isDownForMaintenance())
		{
			Response::current()->response503(true);
		}

		//Get all the constants
		list($namespace, $defaultControllerName, $homeControllerName, $requestMethod, $requestType, $segments) = $this->getAllConstants();

		//Get the controller and make instance of defaultController
		list($controllerName, $remainingSegments) = $this->getController($namespace, $defaultControllerName, $homeControllerName, $requestType, $segments);
		if (!$controllerName)
		{
			Response::current()->response404();
		}

		//Get the name of the method
		list($route, $parameters) = $this->getRoute($controllerName, $requestMethod, $remainingSegments);
		if (!$route
			|| !$this->areParametersValid($route->parameters, $parameters))
		{
			Response::current()->response404();
		}

		//Call the method
		return $this->callMethod($defaultControllerName, $controllerName, $route, $parameters);
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

	/**
	 * Call the method of the controller and return the response
	 * @param string $defaultControllerName
	 * @param string $controllerName
	 * @param Route $route
	 * @param array $parameters
	 * @return \Illuminate\Http\Response
	 */
	private function callMethod($defaultControllerName, $controllerName, $route, $parameters)
	{
		//Set the rights required
		Visitor::$requiredRights = array_unique($route->rights);

		//Initialize the controllers
		$controller = app($controllerName);
		$defaultController = app($defaultControllerName);

		//Initialize, call method, finalize
		$defaultController->initialize();
		$controller->initialize();
		$response = call_user_func_array(array($controller, $route->name), $parameters);
		$controller->finalize();

		//set the status of the response
		Response::current()->assign('success', ($response !== false));

		//Return response
		return Response::current()->getResponse();
	}
}
