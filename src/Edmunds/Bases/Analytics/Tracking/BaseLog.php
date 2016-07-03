<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Bases\Analytics\Tracking;

use Edmunds\Analytics\AnalyticsManager;
use Edmunds\Bases\Structures\BaseStructure;
use Edmunds\Http\Client\Visitor;
use Edmunds\Http\Request;
use Edmunds\Localization\Format\DateTime;
use Edmunds\Registry;
use Exception;
use Throwable;

/**
 * The log base to extend from
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
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

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
			$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
			$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		}

		// set values
		$this->ip = $ip;
		$this->url = $url;
		$this->host = $host;
		$this->path = (!$path || $path[0] != '/') ? "/$path" : $path;
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

			$time = new \DateTime('now', new \DateTimeZone(config('edmunds.system.timezone')));
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
