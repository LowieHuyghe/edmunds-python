<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
  */

namespace Core\Http\Client;

use Core\Bases\Structures\BaseStructure;
use Core\Foundation\Helpers\MiscHelper;
use Core\Auth\Auth;
use Core\Http\Request;
use Core\Http\Response;
use Core\Localization\Models\Localization;
use Core\Localization\Models\Location;
use Core\Auth\Models\User;
use Core\Registry;
use Exception;

/**
 * The helper for the visitor
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
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
			if (app()->isStateful())
			{
				$idKey = config('core.keys.visitor.id.key');

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
			}
			else
			{
				$headerId = config('core.keys.visitor.id.header');

				// check header
				$clientId = $this->request->getHeader($headerId);
				if (!$clientId)
				{
					// generate and save
					$clientId = MiscHelper::generate_uuid();
					$this->response->header($headerId, $clientId);
				}
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
			if (! Localization::isEnabled())
			{
				$localization = $this->getNewLocalization();
			}
			else
			{
				$idKey = config('core.keys.visitor.localization.general');
				$headerLocale = config('core.keys.visitor.localization.locale');
				$headerCurrency = config('core.keys.visitor.localization.currency');
				$headerTimezone = config('core.keys.visitor.localization.timezone');

				// update method
				Localization::saving(function ($localization) use ($idKey, $headerLocale, $headerCurrency, $headerTimezone)
				{
					$response = Response::getInstance();

					// when stateful
					if (app()->isStateful())
					{
						Request::getInstance()->session->set($idKey, $localization);
						$response->cookie($idKey, json_encode($localization->getAttributes()));
					}
					// when stateless
					else
					{
						$response->header($headerLocale, $localization->locale);
						$response->header($headerCurrency, $localization->currency);
						$response->header($headerTimezone, $localization->timezone);
					}

					if (!$localization->user) return false;
				});

				// from user
				if ($user = $this->user)
				{
					$localization = $user->localization;
				}
				// when stateful
				elseif (app()->isStateful())
				{
					// recover from session
					if ($this->request->session->has($idKey))
					{
						$localization = $this->request->session->get($idKey);
					}
					// recover from cookie
					elseif ($localizationJson = $this->request->getCookie($idKey))
					{
						if ($localizationJson = json_decode($localizationJson, true))
						{
							$localization = Localization::recover($localizationJson);
						}
					}
				}
				// when stateless
				else
				{
					$localization = new Localization();
					$localization->locale = $this->request->getHeader($headerLocale);
					$localization->currency = $this->request->getHeader($headerCurrency);
					$localization->timezone = $this->request->getHeader($headerTimezone);
				}

				// check for error
				if (isset($localization) && $localization)
				{
					$check = array('locale', 'currency', 'timezone', 'measurement');
					$newLocalization = null;

					foreach ($check as $attribute)
					{
						if (is_null($localization->$attribute))
						{
							if (!$newLocalization) $newLocalization = $this->getNewLocalization();

							// fill in
							$localization->$attribute = $newLocalization->getAttributes()[$attribute];
						}
					}

					// changes were made so save it
					if ($newLocalization)
					{
						$localization->save();
					}
					// if not in session or cookie, save it
					elseif (!$this->request->session->get($idKey) || !$this->request->getCookie($idKey))
					{
						unset($localization->user_id);
						$localization->save();
					}
				}
				// make new and save
				else
				{
					$localization = $this->getNewLocalization();
					$localization->save();
				}
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
			if (! Location::isEnabled())
			{
				$location = $this->getNewLocation();
			}
			else
			{
				$idKey = config('core.keys.visitor.location.general');
				$cacheKey = $idKey . '_' . $this->request->ip;

				// update method
				Location::saving(function ($location) use ($idKey, $cacheKey)
				{
					// save in session when stateful
					if (app()->isStateful())
					{
						Request::getInstance()->session->set($idKey, $location);
					}
					// save in cache when stateless
					else
					{
						Registry::cache()->set($cacheKey, $location, 60 * 24 * 4); // 4 days because of default lifecycle of DHCP
					}

					if (!$location->user) return false;
				});

				// from user
				if ($user = $this->user)
				{
					$location = $user->location;
				}
				// recover from session when stateful
				elseif (app()->isStateful())
				{
					if ($this->request->session->has($idKey))
					{
						$location = $this->request->session->get($idKey);
					}
				}
				// use cache when stateless
				else
				{
					if (Registry::cache()->has($cacheKey))
					{
						$location = Registry::cache()->get($cacheKey);
					}
				}

				// no location or ip not matching
				if (!isset($location) || !$location || $location->ip != $this->request->ip)
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
