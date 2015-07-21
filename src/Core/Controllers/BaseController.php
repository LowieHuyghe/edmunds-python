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
use LH\Core\Helpers\VisitorHelper;
use LH\Core\Models\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
	public static $routeMethods = array(
//		'index' => array(),
//		'/' => array('p' => array('\d+')),
//		1 => array(
//			'user' => array('p' => array('\d+', '\d+', '\d+')),
//		),
	);

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
	 * @var SessionInterface
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
		$this->response = ResponseHelper::getInstance();
		$this->session = $this->request->getSession();
		$this->input = InputHelper::getInstance();
		$this->validator = new ValidationHelper($this->input->all());
		$this->visitor = VisitorHelper::getInstance($this->request);

		$this->checkRoles();
	}

	/**
	 * Check if user has all the required roles
	 */
	private function checkRoles()
	{
		//If no roles required, return
		if (count(VisitorHelper::$requiredRoles) === 0)
		{
			return;
		}

		if ($this->visitor->isLoggedIn())
		{
			//There are roles, and user is logged in

			$userRoles = User::find($this->visitor->user->id)->roles()->get()->lists('id')->toArray();

			$requiredRoles = array_unique(VisitorHelper::$requiredRoles);
			$userRoles = array_unique($userRoles);

			//If count matches, return
			if (count(array_intersect($userRoles, $requiredRoles)) == count($requiredRoles))
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
