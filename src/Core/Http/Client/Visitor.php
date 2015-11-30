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
 * @property Session $session
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
	 * Constructor
	 * @param Request $request
	 */
	public function __construct($request)
	{
		parent::__construct();

		$this->session = $request->session;
		$this->context = new Context($request->userAgent);
		$this->location = new Location($request->ip);
		//$this->user = $auth->user;

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
		$clientId = $this->session->get($idKey);
		if (!$clientId)
		{
			//Then check cookie
			$clientId = app(Request::class)->getCookie($idKey);
			if (!$clientId)
			{
				//Otherwise generate and save
				$clientId = MiscHelper::generate_uuid();
				$this->session->set($idKey, $clientId);
				app(Response::class)->assignCookie($idKey, $clientId);
			}
			else
			{
				$this->session->set($idKey, $clientId);
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
		return Auth::current()->loggedIn;
	}

}
