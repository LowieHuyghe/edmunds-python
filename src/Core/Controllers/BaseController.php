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
	public $routeMethods = array(
		//0 => array( //Place of method in uri
		//	'get' => 1 //ex: '/user/123'
		//	'getIndex' => 0 //ex: '/user/'
		//	'getHome' => 0 //ex: '/user/home'
		//	'getSomething' => 1 //ex: '/user/something/1'
		//),
		//1 => array( //Place of method in uri
		//	'getContacts' => 2 //Required Parameters, ex: '/user/123/contacts/45'
		//),
	);

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
	 * The constructor for the BaseController
	 */
	function __construct()
	{
		$this->request = self::getRouter()->getCurrentRequest();
		$this->response = ResponseHelper::getInstance();
		$this->session = $this->request->getSession();
		$this->input = InputHelper::getInstance();
		$this->validator = new ValidationHelper($this->input->all());
	}

	/**
	 * Function called after construct
	 */
	public function initialize()
	{
		//
	}

}
