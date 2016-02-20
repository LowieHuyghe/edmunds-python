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

namespace Core\Bases\Analytics\Tracking;

use Core\Analytics\AnalyticsManager;
use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Localization\Format\DateTime;
use Core\Registry;
use Exception;
use Throwable;

/**
 * The log base to extend from
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string $visitorId
 * @property int $userId
 * @property DateTime $time local for user
 * @property string $locale
 *
 * @property string $ip
 * @property string $url
 * @property string $host
 * @property string $path
 * @property string $referrer
 * @property string $userAgent
 *
 * @property string $environment
 */
class BaseLog extends BaseStructure
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		if (AnalyticsManager::isEnabled())
		{
			$this->setRequestParameters();
			$this->setVisitorParameters();

			// set environment info
			$this->environment = app()->environment();
		}
	}

	/**
	 * Set the parameters fetched from the request
	 */
	protected function setRequestParameters()
	{
		try
		{
			$request = Request::getInstance();
		}
		catch(Exception $e) {}
		catch(Throwable $e) {}

		// set request info
		if (isset($request))
		{
			$ip = $request->ip;
			$url = $request->fullUrl;
			$host = $request->host;
			$path = $request->path;
			$referrer = $request->referrer;
			$userAgent = $request->userAgent;
		}
		// try to backup on server-variables
		else
		{
			$request = app('request');

			$ip = $request->ip();
			$url = $request->fullUrl();
			$host = $request->getHttpHost();
			$path = $request->path();
			$referrer = $_SERVER['HTTP_REFERER'] ?? null;
			$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
		}

		// set values
		$this->ip = $ip;
		$this->url = $url;
		$this->host = $host;
		$path = (!$path || $path[0] != '/') ? "/$path" : $path;
		if ($referrer)
		{
			$this->referrer = $referrer;
		}
		$this->userAgent = $userAgent;
	}

	/**
	 * Set the parameters fetched from the visitor
	 */
	protected function setVisitorParameters()
	{
		// fetch visitor info
		try
		{
			$visitor = Visitor::getInstance();
		}
		catch(Exception $e) {}
		catch(Throwable $e) {}

		// set visitor info
		if (isset($visitor))
		{
			$visitorId = $visitor->id;
			$userId = $visitor->loggedIn ? $visitor->user->id : null;

			$time = new DateTime();
			$locale = $visitor->localization->locale;
		}
		else
		{
			$visitorId = app('request')->cookie('visitor_id', null);
			$userId = app('auth')->check() ? app('auth')->user()->id : null;

			$time = new \DateTime('now', new \DateTimeZone(config('core.system.timezone')));
			$locale = null;
		}

		// set user info
		$this->visitorId = $visitorId;
		$this->userId = $userId;
		$this->time = $time;
		$this->locale = $locale;
	}

	/**
	 * Log the damn thing
	 * @param string $driver
	 */
	public function log($driver = null)
	{
		Registry::warehouse($driver)->log($this);
	}
}
