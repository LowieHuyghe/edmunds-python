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
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use LH\Core\Jobs\LogQueue;

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
	use DispatchesJobs;

	/**
	 * Start time of request
	 * @var integer
	 */
	public static $startMicroTime;
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
	 * Constructor
	 */
	private function __construct()
	{
		$this->init();
	}

	/**
	 * Initiate the tracker
	 */
	private function init()
	{
		//Get stuff needed for initialization
		$request = RequestHelper::getInstance();
		$visitor = VisitorHelper::getInstance();

		//Set to debugging when not in production
		if (!$request->isProductionEnvironment())
		{
			$GLOBALS['PIWIK_TRACKER_DEBUG'] = true;
		}

		//Init piwik-tracker
		\PiwikTracker::$URL = Config::get('app.logging.url');
		$piwikTracker = new \PiwikTracker(Config::get('app.logging.siteid'));
		$piwikTracker->setTokenAuth(Config::get('app.logging.token'));

		//Check if logged in and set userId
		if ($visitor->isLoggedIn())
		{
			$piwikTracker->setUserId($visitor->user->id);
		}
		//Set the browser info
		$piwikTracker->setUserAgent($visitor->browser->getUserAgent());
		$piwikTracker->setBrowserLanguage($visitor->browser->getLanguage());
		$piwikTracker->setIp($request->getIp());
		$piwikTracker->setUrl($request->getFullUrl());
		$piwikTracker->setUrlReferrer($request->getReferer());
		$piwikTracker->setPageCharset('utf-8');

		//Set the tracker
		$this->piwikTracker = $piwikTracker;

	}

	/**
	 * Return the piwiktracker
	 * @return \PiwikTracker
	 */
	public function getTracker()
	{
		return $this->piwikTracker;
	}

	/**
	 * Log the view of the user
	 */
	public function logView()
	{
		$requestEndTime = microtime(true);
		$requestGenerationTime = ($requestEndTime - self::$startMicroTime) / 1000.0;

		$this->piwikTracker->setGenerationTime($requestGenerationTime);
		$url = $this->piwikTracker->getUrlTrackPageView(RequestHelper::getInstance()->getPath());

		$this->queueLog($url);
	}

	/**
	 * Queue the logging
	 * @param string $url
	 */
	private function queueLog($url)
	{
		$this->dispatch((new LogQueue($url))->onQueue(Config::get('app.logging.queue')));
	}
}
