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
 * @property Environment $environment
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
	public static function current()
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
		$request = Request::current();

		$this->session = $request->session;
		$this->environment = new Environment($request->userAgent);
		$this->location = new Location($request->ip);

		if (app('auth')->check())
		{
			$authUser = app('auth')->user();
			$this->user = User::findOrFail($authUser->id);
			$this->response->assign('__login', $authUser);
		}
		else
		{
			$this->user = null;
		}

		$this->localization = new Localization($this->environment, $this->location, $this->user);
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
