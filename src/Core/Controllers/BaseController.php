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

use Core\Helpers\ControllerHelper;
use Core\Helpers\ValidationHelper;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;

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

	use DispatchesCommands,
		ValidatesRequests;

	/**
	 * Facade to access other controllers
	 * @var ControllerHelper
	 */
	private static $ch;

	/**
	 * The current request
	 * @var Request
	 */
	protected $request;

	/**
	 * The current route
	 * @var Route
	 */
	protected $route;

	/**
	 * The validator
	 * @var ValidationHelper
	 */
	protected $validator;

	/**
	 * The constructor for the BaseController
	 */
	function __construct()
	{
		parent::__construct();

		$this->request = self::getRouter()->getCurrentRequest();
		$this->route = self::getRouter()->getCurrentRoute();
		$this->validator = new ValidationHelper(Input::all());
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
