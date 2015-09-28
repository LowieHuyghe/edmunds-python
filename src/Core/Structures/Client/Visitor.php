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

namespace LH\Core\Structures\Client;

use Illuminate\Support\Facades\Auth;
use LH\Core\Helpers\BaseHelper;
use LH\Core\Models\User;
use LH\Core\Structures\Http\Request;

/**
 * The helper for the visitor
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class Visitor extends BaseHelper
{
	/**
	 * Instance of the visitor-helper
	 * @var Visitor
	 */
	private static $instance;

	/**
	 * The rights the user is required to have
	 * @var array
	 */
	public static $requiredRights;

	/**
	 * Fetch instance of the visitor-helper
	 * @return Visitor
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Visitor();
		}

		return self::$instance;
	}

	/**
	 * @var User
	 */
	public $user;

	/**
	 * @var Session
	 */
	public $session;

	/**
	 * @var Browser
	 */
	public $browser;

	/**
	 * @var Location
	 */
	public $location;

	/**
	 * @var Localization
	 */
	public $localization;

	/**
	 * Constructor
	 */
	private function __construct()
	{
		$request = Request::getInstance();

		$this->session = $request->getSession();
		$this->browser = new Browser($request->getUserAgent());
		$this->location = new Location($request->getIp());

		if (Auth::check())
		{
			$authUser = Auth::user();
			$this->user = User::findOrFail($authUser->id);
			$this->response->assign('__login', $authUser);
		}

		$this->localization = new Localization($this->browser, $this->location, $this->user);
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
