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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Illuminate\Support\Facades\Input;
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
	 * The current request
	 * @var Request
	 */
	protected $request;

	/**
	 * The current session
	 * @var SessionInterface
	 */
	protected $session;

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
		$this->request = self::getRouter()->getCurrentRequest();
		$this->session = $this->request->getSession();
		$this->validator = new ValidationHelper(Input::all());
	}

	/**
	 * Function called after construct
	 */
	public function initialize()
	{
		//
	}

}
