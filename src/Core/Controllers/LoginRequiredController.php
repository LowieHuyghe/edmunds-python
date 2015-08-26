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
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use LH\Core\Models\User;

/**
 * Controller to extend from which requires the user to log in
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class LoginRequiredController extends BaseController
{
	/**
	 * The intended login route key for the session
	 */
	const	SESSION_KEY_LOGIN_INTENDED_ROUTE = 'LOGIN_INTENDED_ROUTE';

	/**
	 * The logged in user
	 * @var User
	 */
	protected $user;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->checkLogin();

		$this->response->assign('__login', $this->visitor->user);
	}

	/**
	 * Check if user is logged in
	 */
	private function checkLogin()
	{
		//If user is not logged in, redirect to other page
		if (!$this->visitor->isLoggedIn())
		{
			$this->visitor->setIntendedRoute();

			$loginRoute = Config::get('app.routing.loginroute');
			$this->response->responseRedirect($loginRoute);
		}
	}
}