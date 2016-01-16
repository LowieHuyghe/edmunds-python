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
use Core\Models\Location;
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
 * @property Location $location
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
	 * The location
	 * @var Location
	 */
	protected $visitorLocation;

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
			$idKey = 'visitor_localization';

			// update method for session and cookies
			Localization::saving(function ($localization) use ($idKey)
			{
				$localizationJson = json_encode($localization->getAttributes());

				Request::getInstance()->session->set($idKey, $localizationJson);
				Response::getInstance()->cookie($idKey, $localizationJson);
			});


			// from user
			if ($user = $this->user)
			{
				$localization = $user->localization;
			}
			else
			{
				// from session
				$localizationJson = $this->request->session->get($idKey);
				if (!$localizationJson)
				{
					// from cookie
					$localizationJson = $this->request->getCookie($idKey);
					if ($localizationJson)
					{
						$this->request->session->set($idKey, $localizationJson);
					}
				}
				elseif (!$this->request->getCookie($idKey))
				{
					$this->response->cookie($idKey, $localizationJson);
				}

				// recover
				if ($localizationJson)
				{
					$localization = Localization::recover(json_decode($localizationJson, true));
				}
			}

			// check for error
			if (isset($localization) && $localization)
			{
				$check = array('locale', 'currency', 'timezone');
				$newLocalization = null;

				foreach ($check as $attribute)
				{
					if (is_null($localization->$attribute))
					{
						if (!$newLocalization) // fetch new if not already
						{
							$newLocalization = $this->getNewLocalization();
						}
						// fill in
						$localization->$attribute = $newLocalization->$attribute;
					}
				}
				// changes were made so save it
				if ($newLocalization)
				{
					$localization->save();
				}
			}
			// make new and save
			else
			{
				$localization = $this->getNewLocalization();
				$localization->save();
			}

			// and set to visitor
			$this->visitorLocalization = $localization;
		}

		return $this->visitorLocalization;
	}

	/**
	 * Fetch a new initialized instance of Localization
	 * @return Localization
	 */
	protected function getNewLocalization()
	{
		$localization = new Localization();
		$localization->initialize($this->location->timezone, $this->location->country_code, $this->context->locale, $this->context->localeFallback);
		return $localization;
	}

	/**
	 * Fetch the location
	 * @return Location
	 */
	protected function getLocationAttribute()
	{
		if (!isset($this->visitorLocation))
		{
			$idKey = 'visitor_location';

			// update method for session
			Location::saving(function ($location) use ($idKey)
			{
				$locationJson = json_encode($location->getAttributes());

				Request::getInstance()->session->set($idKey, $locationJson);
			});

			// from user
			if ($user = $this->user)
			{
				$location = $user->location;

				// check if needs to update
				if ($location->ip != $this->request->ip)
				{
					$location->initialize($this->request->ip);
					$location->save();
				}
			}
			else
			{
				// from session
				$locationJson = $this->request->session->get($idKey);

				// recover
				if ($locationJson)
				{
					$location = Location::recover(json_decode($locationJson, true));
				}
				// make new and save
				else
				{
					$location = $this->getNewLocation();
					$location->save();
				}
			}

			// and set to visitor
			$this->visitorLocation = $location;
		}

		return $this->visitorLocation;
	}

	/**
	 * Fetch a new initialized instance of Location
	 * @return Location
	 */
	protected function getNewLocation()
	{
		$location = new Location();
		$location->initialize($this->request->ip);
		return $location;
	}

}
