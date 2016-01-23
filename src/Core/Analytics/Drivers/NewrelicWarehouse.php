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

namespace Core\Analytics\Drivers;

use Core\Analytics\Drivers\BaseWarehouse;
use Core\Bases\Analytics\Tracking\ErrorLog;

/**
 * The piwik warehouse driver
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property bool $loaded
 * @property string $license
 * @property string $appName
 */
class NewrelicWarehouse extends BaseWarehouse
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->appname = config('app.analytics.newrelic.appname');
		$this->license = config('app.analytics.newrelic.license') ?: ini_get('newrelic.license');

		$this->loaded = $this->license && extension_loaded('newrelic');
	}

	/**
	 * Log something
	 * @param  BaseLog $log
	 */
	public function log($log)
	{
		// check if loaded
		if (!$this->loaded)
		{
			return;
		}

		// get the log attributes
		$logAttributes = $log->getAttributes();

		// process the log
		if ($log instanceof ErrorLog)
		{
			$message = $log->message->getValue();
			$exception = $log->exception->getValue();

			newrelic_notice_error($message, $exception);
		}
	}
}
