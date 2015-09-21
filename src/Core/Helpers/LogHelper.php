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
use Illuminate\Support\Facades\Config;

/**
 * The helper responsible for logging
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class LogHelper extends BaseHelper
{
	/**
	 * Instance of the response-helper
	 * @var LogHelper
	 */
	private static $instance;

	/**
	 * Fetch instance of the response-helper
	 * @return LogHelper
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new LogHelper();
		}

		return self::$instance;
	}

	/**
	 * Instance of the piwikTracker
	 * @var \PiwikTracker
	 */
	private $piwikTracker;

	/**
	 * Instance of the visitor
	 * @var VisitorHelper
	 */
	private $visitor;

	/**
	 * @param Request $request
	 */
	private function __construct($request)
	{
		$this->visitor = VisitorHelper::getInstance();

		$this->init();
	}

	/**
	 * Initiate the tracker
	 */
	private function init()
	{
		//Set to debugging when not in production
		if (!RequestHelper::getInstance()->isProductionEnvironment())
		{
			$GLOBALS['PIWIK_TRACKER_DEBUG'] = true;
		}

		//Init piwik-tracker
		\PiwikTracker::$URL = Config::get('app.logging.url');
		$piwikTracker = new \PiwikTracker(Config::get('app.logging.siteid'));

		//Check if logged in and set userId
		if ($this->visitor->isLoggedIn())
		{
			$piwikTracker->setUserId($this->visitor->user->id);
		}
		//Set the browser info
		$piwikTracker->setUserAgent($this->visitor->browser->getUserAgent());
		$piwikTracker->setBrowserLanguage($this->visitor->browser->getLanguage());
		$piwikTracker->setIp($this->visitor->request->getIp());
		$piwikTracker->setUrl($this->visitor->request->getFullUrl());
		$piwikTracker->setUrlReferrer($this->visitor->request->refer);
		//Set the location
		$piwikTracker->setCountry($this->visitor->location->countryName);
		$piwikTracker->setCity($this->visitor->location->cityName);
		$piwikTracker->setLongitude($this->visitor->location->longitude);
		$piwikTracker->setLatitude($this->visitor->location->latitude);

		//Set the tracker
		$this->piwikTracker = $piwikTracker;
	}

	public function error()
	{
		//
	}
}
