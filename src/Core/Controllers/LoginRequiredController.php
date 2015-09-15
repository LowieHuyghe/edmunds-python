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

use Illuminate\Support\Facades\Config;
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
			$loginRoute = Config::get('app.routing.loginroute');
			$this->response->responseRedirect($loginRoute, null, true);
		}
	}
}