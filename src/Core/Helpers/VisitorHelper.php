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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
	const	INTENDED_ROUTE_DEFAULT		= 'visitor_intended_route_default';

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
	 * @var SessionInterface
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
	 * Constructor
	 * @param Request $request
	 */
	private function __construct(&$request)
	{
		$this->request = $request;
		$this->ip = $request->ip();
		$this->session = $request->getSession();
		$this->browser = new BrowserHelper($request->server('HTTP_USER_AGENT'));
		$this->location = new LocationHelper($this->ip);

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

	/**
	 * Set the intended route of the visitor
	 * @param string $route
	 * @param string $key
	 */
	public function setIntendedRoute($route = null, $key = self::INTENDED_ROUTE_DEFAULT)
	{
		if (!$route)
		{
			$route = $this->request->path();
		}

		$this->session->set($key, $route);
	}

	/**
	 * Get the visitor his intended route
	 * @param string $key
	 * @return mixed
	 */
	public function getIntendedRoute($key = self::INTENDED_ROUTE_DEFAULT)
	{
		return $this->session->get($key);
	}
}
