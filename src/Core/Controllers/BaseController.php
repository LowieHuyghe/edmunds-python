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

namespace Core\Controllers;

use Core\Structures\Client\Input;
use Core\Structures\Http\Request;
use Core\Structures\Http\Response;
use Core\Structures\Client\Visitor;
use Core\Structures\Io\Validation;
use Core\Structures\Registry\Registry;
use Laravel\Lumen\Routing\Controller;
use Laravel\Lumen\Routing\DispatchesJobs;
use Laravel\Lumen\Routing\ValidatesRequests;

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

	use DispatchesJobs, ValidatesRequests;

	/**
	 * List of the accepted methods for routing
	 * @var array
	 */
//	public static $routeMethods = array(
//		'index' => array(),
//		'/' => array('p' => array('\d+')),
//		1 => array(
//			'user' => array('p' => array('\d+', '\d+', '\d+')),
//		),
//	);

	/**
	 * Get the accepted methods for routing
	 * @return array
	 */
	public static function getRouteMethods()
	{
		return static::$routeMethods;
	}

	/**
	 * Set the accepted methods for routing
	 * @param array $routeMethods
	 */
	public static function setRouteMethods($routeMethods)
	{
		static::$routeMethods = $routeMethods;
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
	 * The registry
	 * @var Registry
	 */
	protected $registry;

	/**
	 * The constructor for the BaseController
	 */
	function __construct()
	{
		$this->request = Request::current();
		$this->response = Response::current();
		$this->input = Input::current();
		$this->validator = new Validation($this->input->all());
		$this->visitor = Visitor::current();
		$this->registry = Registry::current();

		$this->checkRights();
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

		if ($this->visitor->isLoggedIn())
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
		elseif (is_a($this, LoginRequiredController::class))
		{
			//Roles and not logged in, but LoginRequired: user will be redirected to log in
			return;
		}

		//Visitor is not authorized to be here
		$this->response->response401();
	}

	/**
	 * Function called after construct
	 */
	public function initialize()
	{
		//
	}

	/**
	 * Function called after initialization
	 */
	public function preRender()
	{
		//
	}

	/**
	 * Function called after method
	 */
	public function postRender()
	{
		//
	}

}
