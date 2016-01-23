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

use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Localization\DateTime;
use Core\Registry\Registry;

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
 * @property string $charset
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

		$request = Request::getInstance();
		$visitor = Visitor::getInstance();

		// set visitor info
		$this->visitorId = $visitor->id;
		if ($visitor->loggedIn)
		{
			$this->userId = $visitor->user->id;
		}
		$this->time = new DateTime();
		$this->locale = $visitor->localization->locale;

		// set request info
		$this->ip = $request->ip;
		$this->url = $request->fullUrl;
		$this->host = $request->host;
		$path = $request->path;
		$this->path = (!$path || $path[0] != '/') ? '/' . $path : $path;
		if ($referrer = $request->referrer)
		{
			$this->referrer = $referrer;
		}
		$this->userAgent = $visitor->context->userAgent;

		// set environment info
		$this->charset = 'utf-8';
		$this->environment = app()->environment();
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
