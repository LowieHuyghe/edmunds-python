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
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Input;
use LH\Core\Helpers\ControllerHelper;
use LH\Core\Helpers\ValidationHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;

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
	 * Facade to access other controllers
	 * @var ControllerHelper
	 */
	private static $ch;

	/**
	 * The current route
	 * @var Route
	 */
	protected $route;

	/**
	 * The current request
	 * @var Request
	 */
	protected $request;

	/**
	 * The validator
	 * @var ValidationHelper
	 */
	protected $validator;

	/**
	 * The constructor for the BaseController
	 */
	/**
	 * @param \Illuminate\Routing\Router $router
	 */
	function __construct($router = null)
	{
		if ($router)
		{
			self::setRouter($router);
		}
		$this->route = self::getRouter()->getCurrentRoute();
		$this->request = self::getRouter()->getCurrentRequest();
		$this->validator = new ValidationHelper(Input::all());
	}

	/**
	 * Function called after construct
	 */
	public function initialize()
	{
		//
	}

	/**
	 * Facade to access other controllers
	 * @return ControllerHelper
	 */
	public function ch()
	{
		if (!self::$ch) {
			self::$ch = new ControllerHelper();
		}
		return self::$ch;
	}

}
