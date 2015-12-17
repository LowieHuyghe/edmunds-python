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
use Core\Models\Localization;
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
			self::$instance = new Visitor(Request::getInstance(), Response::getInstance());
		}

		return self::$instance;
	}

	/**
	 * The current request
	 * @var Request
	 */
	protected $request;

	/**
	 * The current response
	 * @var Response
	 */
	protected $response;

	/**
	 * The id
	 * @var string
	 */
	protected $visitorId;

	/**
	 * The localization
	 * @var Localization
	 */
	protected $visitorLocalization;

	/**
	 * Constructor
	 * @param Request $request
	 * @param Response $response
	 */
	public function __construct($request, $response)
	{
		parent::__construct();

		$this->request = $request;
		$this->response = $response;
		$this->context = new Context($request->userAgent);
	}

	/**
	 * Get the id of the visitor
	 * @return string
	 */
	protected function getIdAttribute()
	{
		if (!isset($this->visitorId))
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
					$this->response->cookie($idKey, $clientId);
				}
				$this->request->session->set($idKey, $clientId);
			}

			$this->visitorId = $clientId;
		}

		return $this->visitorId;
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

	/**
	 * Fetch the localization
	 * @return Localization
	 */
	protected function getLocalizationAttribute()
	{
		if (!isset($this->visitorLocalization))
		{
			$idKey = 'localization';

			// update method for session and cookies
			$updateLocalization = function ($localization) use ($idKey)
			{
				$localizationAttributes = $localization->__toString();

				Request::getInstance()->session->set($idKey, $localizationAttributes);
				Response::getInstance()->cookie($idKey, $localizationAttributes);
			};
			Localization::saving($updateLocalization);


			// from user
			if ($user = $this->user)
			{
				$localization = $user->localization;
			}
			else
			{
				// from session
				$localizationAttributes = $this->request->session->get($idKey);
				if (!$localizationAttributes)
				{
					// from cookie
					$localizationAttributes = $this->request->getCookie($idKey);
					if ($localizationAttributes)
					{
						$this->request->session->set($idKey, $localizationAttributes);
					}
				}

				// recover
				if ($localizationAttributes)
				{
					$localization = Localization::recover(json_decode($localizationAttributes, true));
					dd($localization);
				}
				// make new and save
				else
				{
					$localization = new Localization();
					$localization->initialize();

					$updateLocalization($localization);
				}
			}

			// and set to visitor
			$this->visitorLocalization = $localization;
		}

		return $this->visitorLocalization;
	}

}
