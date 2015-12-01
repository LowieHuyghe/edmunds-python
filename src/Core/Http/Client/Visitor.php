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

namespace Core\Http\Client;

use Core\Bases\Structures\BaseStructure;
use Core\Helpers\MiscHelper;
use Core\Http\Client\Auth;
use Core\Http\Request;
use Core\Http\Response;
use Core\Models\User;

/**
 * The helper for the visitor
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $id
 * @property User $user
 * @property bool $loggedIn
 * @property Context $context
 * @property Location $location
 * @property Localization $localization
 */
class Visitor extends BaseStructure
{
	/**
	 * The rights the user is required to have
	 * @var array
	 */
	public static $requiredRights;

	/**
	 * Instance of the Visitor-structure
	 * @var Visitor
	 */
	private static $instance;

	/**
	 * Fetch instance of the Visitor-structure
	 * @return Visitor
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Visitor(Request::getInstance());
		}

		return self::$instance;
	}

	/**
	 * The current request
	 * @var Request
	 */
	private $request;

	/**
	 * Constructor
	 * @param Request $request
	 */
	public function __construct($request)
	{
		parent::__construct();

		$this->request = $request;
		$this->context = new Context($request->userAgent);
		$this->location = new Location($request->ip);

		$this->localization = new Localization($this->context, $this->location, $this->user);
	}

	/**
	 * Get the id of the visitor
	 * @return string
	 */
	protected function getIdAttribute()
	{
		$idKey = 'visitor_id';

		//First check session
		$clientId = $this->request->session->get($idKey);
		if (!$clientId)
		{
			//Then check cookie
			$clientId = $this->request->getCookie($idKey);
			if (!$clientId)
			{
				//Otherwise generate and save
				$clientId = MiscHelper::generate_uuid();
				$this->request->session->set($idKey, $clientId);
				$this->request->assignCookie($idKey, $clientId);
			}
			else
			{
				$this->request->session->set($idKey, $clientId);
			}
		}
		return $clientId;
	}

	/**
	 * Check if visitor is logged in
	 * @return bool
	 */
	protected function getLoggedInAttribute()
	{
		return Auth::getInstance()->loggedIn;
	}

	/**
	 * Fetch the current user
	 * @return bool
	 */
	protected function getUserAttribute()
	{
		return Auth::getInstance()->user;
	}

}
