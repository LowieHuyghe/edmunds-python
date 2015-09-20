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
	 * The rights the user is required to have
	 * @var array
	 */
	public static $requiredRights;

	/**
	 * Fetch instance of the visitor-helper
	 * @return VisitorHelper
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new VisitorHelper();
		}

		return self::$instance;
	}

	/**
	 * @var User
	 */
	public $user;

	/**
	 * @var SessionHelper
	 */
	public $session;

	/**
	 * @var BrowserHelper
	 */
	public $browser;

	/**
	 * @var LocationHelper
	 */
	public $location;

	/**
	 * @var LocalizationHelper
	 */
	public $localization;

	/**
	 * Constructor
	 */
	private function __construct()
	{
		$request = RequestHelper::getInstance();

		$this->session = $request->getSession();
		$this->browser = new BrowserHelper($request->getUserAgent());
		$this->location = new LocationHelper($request->getIp());

		if (Auth::check())
		{
			$authUser = Auth::user();
			$this->user = User::findOrFail($authUser->id);
			$this->response->assign('__login', $authUser);
		}

		$this->localization = new LocalizationHelper($this->browser, $this->location, $this->user);
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
