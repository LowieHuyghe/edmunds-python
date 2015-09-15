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
use LH\Core\Helpers\InputHelper;
use LH\Core\Helpers\ResponseHelper;
use LH\Core\Helpers\SessionHelper;
use LH\Core\Helpers\VisitorHelper;
use LH\Core\Helpers\ValidationHelper;
use Illuminate\Http\Request;
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
	 * @var ResponseHelper
	 */
	protected $response;

	/**
	 * The current session
	 * @var SessionHelper
	 */
	protected $session;

	/**
	 * The input
	 * @var InputHelper
	 */
	protected $input;

	/**
	 * The validator
	 * @var ValidationHelper
	 */
	protected $validator;

	/**
	 * The visitor
	 * @var VisitorHelper
	 */
	protected $visitor;

	/**
	 * The constructor for the BaseController
	 */
	function __construct()
	{
		$this->request = self::getRouter()->getCurrentRequest();
		$this->request->setSession(SessionHelper::getInstance($this->request->getSession()));
		$this->response = ResponseHelper::getInstance();
		$this->response->setRequest($this->request);
		$this->session = $this->request->session();
		$this->input = InputHelper::getInstance();
		$this->validator = new ValidationHelper($this->input->all());
		$this->visitor = VisitorHelper::getInstance($this->request);

		$this->checkRights();
	}

	/**
	 * Check if user has all the required rights
	 */
	private function checkRights()
	{
		//If no roles required, return
		if (count(VisitorHelper::$requiredRights) === 0)
		{
			return;
		}

		if ($this->visitor->isLoggedIn())
		{
			//There are rights, and user is logged in

			$hasRights = true;
			foreach (VisitorHelper::$requiredRights as $rightId)
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
