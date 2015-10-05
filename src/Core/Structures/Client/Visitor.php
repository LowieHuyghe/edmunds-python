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

namespace Core\Structures\Client;

use Illuminate\Support\Facades\Auth;
use Core\Models\User;
use Core\Structures\BaseStructure;
use Core\Structures\Http\Request;

/**
 * The helper for the visitor
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property User $user
 * @property Session $session
 * @property Browser $browser
 * @property Location $location
 * @property Localization $localization
 */
class Visitor extends BaseStructure
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
	 * Constructor
	 */
	public function __construct()
	{
		$request = Request::getInstance();

		$this->session = $request->session;
		$this->browser = new Browser($request->userAgent);
		$this->location = new Location($request->ip);

		if (Auth::check())
		{
			$authUser = Auth::user();
			$this->user = User::findOrFail($authUser->id);
			$this->response->assign('__login', $authUser);
		}
		else
		{
			$this->user = null;
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
