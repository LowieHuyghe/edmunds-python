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

namespace Core\Bases\Http\Controllers;

use Core\Http\Client\Input;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Client\Visitor;
use Core\Validation\Validator;
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
	 * The default output type of the response, only used when set
	 * @var int
	 */
	protected $outputType; //Response::TYPE_VIEW by default

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
		$this->visitor = Visitor::getInstance();
		$this->input = Input::getInstance();

		if (isset($this->outputType))
		{
			$this->response->outputType = $this->outputType;
		}
	}

	/**
	 * The response flow of the controller
	 * @param string $method
	 * @param array $parameters
	 */
	public function responseFlow($method, $parameters)
	{
		//Assign default values
		$this->assignDefaults();

		//Initialiaz this controller
		$this->initialize();

		//Call the tight method
		$response = call_user_func_array(array($this, $method), $parameters);

		//Finalize this controller
		$this->finalize();

		//set the status of the response
		if ($response === true || $response === false)
		{
			$this->response->assign('success', $response);
		}

		return $this->response->getResponse();
	}

	/**
	 * Assign some default values
	 */
	protected function assignDefaults()
	{
		$this->response->assign('__root', $this->request->root);
		$this->response->assign('__siteName', app()->getName());

		$this->response->assign('__local', app()->isLocal());

		$this->response->assign('__login', $this->visitor->user);

		$this->response->assign('__rtl', $this->visitor->localization->rtl);
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
