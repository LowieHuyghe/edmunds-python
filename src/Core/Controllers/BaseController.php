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

namespace LH\Core\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use LH\Core\Structures\Client\Input;
use LH\Core\Structures\Http\Request;
use LH\Core\Structures\Http\Response;
use LH\Core\Structures\Client\Visitor;
use LH\Core\Structures\Validation;
use Illuminate\Routing\Controller;

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
	 * The constructor for the BaseController
	 */
	function __construct()
	{
		$this->request = Request::getInstance();
		$this->response = Response::getInstance();
		$this->input = Input::getInstance();
		$this->validator = new Validation($this->input->all());
		$this->visitor = Visitor::getInstance();

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
		$this->response->responseUnauthorized();
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
