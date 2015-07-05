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

		$authUser = Auth::user();
		if ($authUser)
		{
			$this->user = User::findOrFail($authUser->id);
			$this->response->assign('__login', $authUser);
		}
	}

	/**
	 * Check if user is authenticated
	 * @param array $requiredRoles
	 * @return true
	 */
	public function authenticationCheck($requiredRoles)
	{
		if (!Auth::check()
			|| !$this->checkRoles($requiredRoles))
		{
			$loginRoute = Config::get('app.routing.loginroute');
			$this->response->responseRedirect($loginRoute);
			return false;
		}

		return true;
	}

	/**
	 * Check if user has all the required roles
	 * @param array $requiredRoles
	 * @return bool
	 */
	public function checkRoles($requiredRoles)
	{
		$userRoles = User::find($this->user->id)->roles()->get()->lists('id')->toArray();

		$requiredRoles = array_unique($requiredRoles);
		$userRoles = array_unique($userRoles);

		if (count(array_intersect($userRoles, $requiredRoles)) == count($requiredRoles))
		{
			return true;
		}
		return $this->response->responseUnauthorized();
	}
}