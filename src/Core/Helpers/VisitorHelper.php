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

namespace LH\Core\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LH\Core\Models\User;

/**
 * The helper for the visitor
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class VisitorHelper extends BaseHelper
{
	/**
	 * Instance of the visitor-helper
	 * @var VisitorHelper
	 */
	private static $instance;

	/**
	 * The roles the user is required to have
	 * @var array
	 */
	public static $requiredRoles;

	/**
	 * Fetch instance of the visitor-helper
	 * @param Request $request
	 * @return VisitorHelper
	 */
	public static function getInstance($request)
	{
		if (!isset(self::$instance))
		{
			self::$instance = new VisitorHelper($request);
		}

		return self::$instance;
	}

	/**
	 * @var Request
	 */
	public $request;

	/**
	 * @var User
	 */
	public $user;

	/**
	 * @var string
	 */
	public $ip;

	/**
	 * @var BrowserHelper
	 */
	public $browser;

	/**
	 * Constructor
	 * @param Request $request
	 */
	private function __construct(&$request)
	{
		$this->request = $request;
		$this->ip = $request->ip();
		$this->browser = new BrowserHelper($request->server('HTTP_USER_AGENT'));

		$authUser = Auth::user();
		if ($authUser)
		{
			$this->user = User::findOrFail($authUser->id);
			$this->response->assign('__login', $authUser);
		}
	}

	/**
	 * Check if visitor is logged in
	 * @return bool
	 */
	public function isLoggedIn()
	{
		return (isset($this->user) && $this->user);
	}
}
