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

namespace Core\Bases\Controllers;

use Core\Http\Controllers\Login\LoginRequiredController;
use Core\Http\Client\Input;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Client\Visitor;
use Core\Http\Route;
use Core\Io\Validation\Validation;
use Laravel\Lumen\Routing\Controller;

/**
 * Controller base to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class BaseController extends Controller
{
	/**
	 * Get the accepted methods for routing
	 * @return Route[]
	 */
	public static function getRoutes()
	{
		return array();
	}

	/**
	 * The current request
	 * @var Request
	 */
	protected $request;

	/**
	 * The current request
	 * @var Response
	 */
	protected $response;

	/**
	 * The input
	 * @var Input
	 */
	protected $input;

	/**
	 * The validator
	 * @var Validation
	 */
	protected $validator;

	/**
	 * The visitor
	 * @var Visitor
	 */
	protected $visitor;

	/**
	 * The constructor for the BaseController
	 */
	function __construct()
	{
		$this->request = app(Request::class);
		$this->response = app(Response::class);
		$this->visitor = app(Visitor::class);
		$this->input = Input::current();
		$this->validator = new Validation($this->input->all());

		$this->checkRights();
	}

	/**
	 * [responseFlow description]
	 * @param string $defaultControllerName
	 * @param Route $route
	 * @param array $parameters
	 * @return Illuminate\Http\Response
	 */
	public function responseFlow($defaultControllerName, $route, $parameters)
	{
		//Make default controller
		$defaultController = app($defaultControllerName);

		//Initialize default controller
		$defaultController->initialize();

		//Initialiaz this controller
		$this->initialize();
		//Call the tight method
		$response = call_user_func_array(array($this, $route->name), $parameters);
		//Finalize this controller
		$this->finalize();

		//Finalize default controller
		$defaultController->finalize();

		//set the status of the response
		if ($response === true || $response === false)
		{
			$this->response->assign('success', $response);
		}

		//Return response
		return $this->response->getResponse();
	}

	/**
	 * Check if user has all the required rights
	 */
	private function checkRights()
	{
		//If no roles required, return
		if (count(Visitor::$requiredRights) === 0)
		{
			return;
		}

		if ($this->visitor->loggedIn)
		{
			//There are rights, and user is logged in

			$hasRights = true;
			foreach (Visitor::$requiredRights as $rightId)
			{
				if (!$this->visitor->user->hasRight($rightId))
				{
					$hasRights = false;
					break;
				}
			}

			if ($hasRights)
			{
				return;
			}
		}
		elseif ($this instanceof LoginRequiredController)
		{
			//Roles and not logged in, but LoginRequired: user will be redirected to log in
			return;
		}

		//Visitor is not authorized to be here
		abort(403);
	}

	/**
	 * Function called after construct
	 */
	public function initialize()
	{
		//
	}

	/**
	 * Function called after method
	 */
	public function finalize()
	{
		//
	}

}
